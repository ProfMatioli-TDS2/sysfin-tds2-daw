<?php 
namespace App\Controllers;

use App\Models\Compra;
use App\Models\Fornecedor; // Necessário para o dropdown
use App\Models\Produto;    // Necessário para o dropdown
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception; // Necessário para o try/catch

class CompraController
{
    /**
     * Exibe a tela de registro de compra (GET)
     * ou processa a compra (POST).
     */
    public function registrar() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Dados vêm do formulário (incluindo os inputs escondidos do JS)
                $idFornecedor = $_POST['id_fornecedor'];
                $itens = $_POST['itens'] ?? []; 
                $valorTotal = $_POST['valor_total'];
                
                // (Esta é a lógica da Tarefa #7, que seu Model já faz)
                Compra::registrarCompra($idFornecedor, $itens, $valorTotal);
                
                // ==========================================================
                // CORREÇÃO AQUI
                // Em vez de redirecionar para o relatório de compras,
                // vamos redirecionar para o Dashboard (página inicial).
                // ==========================================================
                header('Location: ' . BASE_URL . '/index.php?url=/');
                exit;

            } catch (Exception $e) {
                // Se o Model (Compra::registrarCompra) der um erro
                $data['error'] = $e->getMessage();
            }
        } 
        
        // (GET) ou (POST com erro): Exibe a view de registro com os dados
        $data = [
            'fornecedores' => Fornecedor::getAll(),
            'produtos' => Produto::getAll(),
            'error' => $data['error'] ?? null
        ];
        
        extract($data);
        require __DIR__ . '/../Views/compras/registrar.php';
    }

    /**
     * Método de Relatório (já concluído na tarefa anterior)
     */
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
                $compras = Compra::getComprasByPeriod($dataInicial, $dataFinal);
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

        extract($data);
        require __DIR__ . '/../Views/compras/report.php';
    }
}