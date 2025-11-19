<?php 
namespace App\Controllers;

// 1. Incluir as classes que vamos usar
use App\Core\Database;
use App\Core\SessionManager; // (Já estava no seu código, agora será usado)
use App\Models\Compra;

class CompraController
{
    /**
     * @param string $viewPath
     * @param array $data 
     */
    private function renderView(string $viewPath, array $data = [])
    {
        
        extract($data); 

        
        $fullPath = __DIR__ . "/../Views/{$viewPath}.php";

        
        if (file_exists($fullPath)) {
            
            include $fullPath;
        } else {
            
            echo "Erro: View não encontrada em " . $fullPath;
        }
    }

    /**
     * Tela de Registro de Compra
     */
    public function registrar()
    {
        // Proteção de acesso (Permitido para Admin, Tesoureiro e Vendedor)
        SessionManager::require_auth(['Administrador', 'Tesoureiro', 'Vendedor']);

        $data = [
            'fornecedores' => \App\Models\Fornecedor::getAll(),
            'produtos' => \App\Models\Produto::getAll(),
        ];

        $this->renderView('compras/registrar', $data);
    }

    /**
     * Método de Relatório (já concluído na tarefa anterior)
     */
    public function report()
    {
        // Proteção de acesso (Permitido para Admin e Tesoureiro)
        SessionManager::require_auth(['Administrador', 'Tesoureiro']);

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
                
                $compraModel = new Compra();
                
                
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

        
        $this->renderView('compras/report', $data);
    }
}