<?php 
namespace App\Controllers;

// 1. Incluir as classes que vamos usar
use App\Core\Database;
use App\Core\SessionManager;
use App\Models\Compra;
use App\Models\Produto; 
use App\Models\Fornecedor;

class CompraController
{
    private static function formatBRLtoFLoat($valor)
    {
        if (empty($valor)) {
            return 0.0;
        }
        $valorLimpo = str_replace(['R$', ' '], '', $valor);
        $valorLimpo = str_replace('.', '', $valorLimpo); 
        $valorLimpo = str_replace(',', '.', $valorLimpo); 
        
        return (float) $valorLimpo;
    }

    private static function renderView(string $viewPath, array $data = [])
    {
        extract($data); 
        $fullPath = __DIR__ . "/../Views/{$viewPath}.php";
        if (file_exists($fullPath)) {
            include $fullPath;
        } else {
            echo "Erro: View não encontrada em " . $fullPath;
        }
    }

    public function registrar()
    {
        SessionManager::start();
        $db = Database::getConnection();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $action = $_GET['action'] ?? null;
            if ($action === 'clear') {
                unset($_SESSION['carrinho_compra']);
                header('Location: /compras/registrar');
                exit;
            }
            if ($action === 'remove_item') {
                $index = $_GET['index'] ?? null;
                if ($index !== null && isset($_SESSION['carrinho_compra']['itens'][$index])) {
                    unset($_SESSION['carrinho_compra']['itens'][$index]);
                    $_SESSION['carrinho_compra']['itens'] = array_values($_SESSION['carrinho_compra']['itens']);
                }
                header('Location: /compras/registrar');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;

            if ($action === 'add_item') {
                $produto_id = $_POST['produto_id'] ?? null;
                $quantidade = (int)($_POST['quantidade'] ?? 1);
                $valor_unitario_str = $_POST['valor_unitario'] ?? '0,00';

                $produto = Produto::getById($produto_id); 
                
                if ($produto && $quantidade > 0 && !empty($valor_unitario_str)) {
                    $preco_float = self::formatBRLtoFLoat($valor_unitario_str);
                    
                    if (!isset($_SESSION['carrinho_compra']['fornecedor_id'])) {
                        $_SESSION['carrinho_compra']['fornecedor_id'] = $_POST['fornecedor_id'];
                    }
                    
                    $item = [
                        'produto_id'   => $produto_id,
                        'nome'         => $produto->nome, 
                        'qtd'          => $quantidade,
                        'preco'        => $preco_float,
                        'subtotal'     => $quantidade * $preco_float
                    ];
                    $_SESSION['carrinho_compra']['itens'][] = $item;
                }
                header('Location: /compras/registrar');
                exit;
            }

            if ($action === 'finalize') {
                $itensDoCarrinho = $_SESSION['carrinho_compra']['itens'] ?? [];
                $totalDaCompra = array_sum(array_column($itensDoCarrinho, 'subtotal'));

                $dadosDaCompra = [
                    'fornecedor_id' => $_SESSION['carrinho_compra']['fornecedor_id'] ?? null,
                    'data_compra'   => $_POST['data_compra'] ?? date('Y-m-d'), 
                    'valor_total'   => $totalDaCompra,
                    'itens'         => $itensDoCarrinho 
                ];

                if (empty($dadosDaCompra['itens']) || empty($dadosDaCompra['fornecedor_id'])) {
                    header('Location: /compras/registrar?status=error&msg=carrinho_vazio');
                    exit;
                }

                $compraModel = new Compra($db);
                $sucesso = $compraModel->salvar($dadosDaCompra); 

                if ($sucesso) {
                    unset($_SESSION['carrinho_compra']); 
                    header('Location: /compras/relatorio?status=success');
                } else {
                    header('Location: /compras/registrar?status=error&msg=salvar_falhou');
                }
                exit;
            }
        }

        $data = [
            'fornecedores' => Fornecedor::getAll(),
            'produtos' => Produto::getAll(),
        ];
        
        $itensAtuais = $_SESSION['carrinho_compra']['itens'] ?? [];
        $totalAtual = array_sum(array_column($itensAtuais, 'subtotal'));
        $fornecedorSelecionado = $_SESSION['carrinho_compra']['fornecedor_id'] ?? null;
        
        $data['itens_compra'] = $itensAtuais;
        $data['total_compra'] = $totalAtual;
        $data['selected_fornecedor_id'] = $fornecedorSelecionado;
        
        self::renderView('compras/registrar', $data);
    }

    public function report()
    {
        $data = [
            'compras' => [], 
            'totalCompras' => 0, 
            'valorTotal' => 0.00, 
            'dataInicial' => '', 
            'dataFinal' => ''
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataInicial = $_POST['data_inicial'] ?? '';
            $dataFinal = $_POST['data_final'] ?? '';

            if ($dataInicial && $dataFinal) {
                $db = Database::getConnection();
                $compraModel = new Compra($db);
                
                $compras = $compraModel->getComprasByPeriod($dataInicial, $dataFinal);
                $totalCompras = count($compras);
                $valorTotal = array_sum(array_column($compras, 'valor_total'));
                
                $data = [
                    'compras' => $compras, 
                    'totalCompras' => $totalCompras, 
                    'valorTotal' => $valorTotal, 
                    'dataInicial' => $dataInicial, 
                    'dataFinal' => $dataFinal
                ];
            }
        }
        self::renderView('compras/report', $data);
    }
}