<?php
namespace App\Controllers;

// 1. IMPORTA O SESSION MANAGER
use App\Core\SessionManager;
use App\Models\Venda;
use App\Models\Cliente;
use App\Models\Produto;
use Exception;

class VendaController
{
    /**
     * Lista todas as vendas (index)
     */
    public function index()
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro', 'Vendedor']);
        
        $vendas = Venda::getAll();
        require __DIR__ . '/../Views/vendas/index.php';
    }

    /**
     * Formulário de criação de venda (create)
     */
    public function create()
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro', 'Vendedor']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }
        $clientes = Cliente::getAll();
        $produtos = Produto::getAll();
        require __DIR__ . '/../Views/vendas/create.php';
    }

    /**
     * Lógica de salvar a venda (store)
     * (Este método é privado, é automaticamente protegido pelo 'create')
     */
    private function store()
    {
        // ... (código do store - já concluído) ...
        if (!isset($_POST['id_cliente']) || empty($_POST['id_cliente'])) {
            echo "Erro: Por favor, selecione um cliente.";
            exit;
        }
        if (!isset($_POST['itens']) || !is_array($_POST['itens']) || empty($_POST['itens'])) {
            echo "Erro: A venda deve conter pelo menos um item.";
            exit;
        }

        $venda = new Venda();
        $venda->id_cliente = $_POST['id_cliente'];
        $venda->itens = $_POST['itens'];

        try {
            $venda->save();
            header('Location: '. BASE_URL . '/index.php?url=/vendas');
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo "Falha ao processar a venda: " . $e->getMessage();
            exit;
        }
    }

    /**
     * Detalhes da venda (details)
     */
    public function details($id)
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro', 'Vendedor']);
        
        $venda = Venda::getById($id);
        if (!$venda) {
            http_response_code(404);
            echo "Venda não encontrada.";
            exit;
        }
        require __DIR__ . '/../Views/vendas/details.php';
    }

    /**
     * NOVO: Relatório de Vendas por Período (Tarefa #12)
     */
    public function report()
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro']);
        
        // Dados padrão para a view
        $data = [
            'vendas' => [],
            'totalVendas' => 0,
            'valorTotalVendido' => 0.0,
            'dataInicial' => '',
            'dataFinal' => '',
            'hasData' => false
        ];

        // REQUISITO #1: Se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataInicial = $_POST['data_inicial'] ?? date('Y-m-d');
            $dataFinal = $_POST['data_final'] ?? date('Y-m-d');
            
            // REQUISITO #2: Busca as vendas no período (o Model já tem)
            $vendas = Venda::getByPeriodo($dataInicial, $dataFinal);
            
            // REQUISITO #3: Calcular Totais
            $totalVendas = count($vendas);
            $valorTotalVendido = array_sum(array_column($vendas, 'valor_total'));
            
            $data = [
                'vendas' => $vendas,
                'totalVendas' => $totalVendas,
                'valorTotalVendido' => $valorTotalVendido,
                'dataInicial' => $dataInicial,
                'dataFinal' => $dataFinal,
                'hasData' => true
            ];
        }

        // Passa os dados para a view
        extract($data);
        require __DIR__ . '/../Views/vendas/report.php';
    }
}