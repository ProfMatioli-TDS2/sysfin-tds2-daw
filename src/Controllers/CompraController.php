<?php 
namespace App\Controllers;

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

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idFornecedor = $_POST['id_fornecedor'];
            $itens = $_POST['itens'];
            $valorTotal = $_POST['valor_total'];

            try {
                Compra::registrarCompra($idFornecedor, $itens, $valorTotal);
                exit;
            } catch (\Exception $e) {
                echo "<h3>Erro: " . $e->getMessage() . "</h3>";
            }
        } else {
            require __DIR__ . '/../Views/compras/registrar.php';
        }
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