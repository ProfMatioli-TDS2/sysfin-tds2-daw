<?php
namespace App\Controllers;

use App\Models\Relatorio;

class RelatorioController
{
    public function index()
    {
        $dataInicial = $_GET['data_inicial'] ?? null;
        $dataFinal = $_GET['data_final'] ?? null;

        $saldoAnterior = 0;
        $movimentacoes = [];
        $totais = ['entradas' => 0, 'saidas' => 0];

        if ($dataInicial && $dataFinal) {
            $saldoAnterior = Relatorio::getSaldoAnterior($dataInicial);
            $movimentacoes = Relatorio::getMovimentacoes($dataInicial, $dataFinal);
            $totais = Relatorio::getTotais($dataInicial, $dataFinal);
        }

        require __DIR__ . '/../Views/relatorio/index.php';
    }
}
