<?php
namespace App\Controllers;

// 1. IMPORTA O SESSION MANAGER
use App\Core\SessionManager;
use App\Models\Venda;
use Dompdf\Dompdf;

class RelatorioVendasController
{
    public function index()
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataInicial = $_POST['data_inicial'];
            $dataFinal = $_POST['data_final'];

            $vendas = Venda::getByPeriodo($dataInicial, $dataFinal);

            ob_start();
            require __DIR__ . '/../Views/relatorios/vendas_periodo.php';
            $html = ob_get_clean();

            $pdf = new Dompdf();
            $pdf->loadHtml($html);
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();
            $pdf->stream("relatorio_vendas.pdf", ["Attachment" => false]);
        } else {
            require __DIR__ . '/../Views/relatorios/filtro_vendas.php';
        }
    }
}