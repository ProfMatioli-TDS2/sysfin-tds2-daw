<?php
namespace App\Controllers;

use App\Models\RelatorioVendas;

class RelatorioVendasController
{
    public function form()
    {
        // Tela para o usuário escolher o período
        require __DIR__ . '/../Views/relatorio_vendas/form.php';
    }

    public function gerar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recebe as datas informadas no formulário
            $dataInicio = $_POST['data_inicio'];
            $dataFim = $_POST['data_fim'];
            
            // Busca os dados das vendas no período
            $vendas = RelatorioVendas::getByPeriodo($dataInicio, $dataFim);
            
            // Exibe o relatório na tela
            require __DIR__ . '/../Views/relatorio_vendas/report.php';
        }
    }
}
