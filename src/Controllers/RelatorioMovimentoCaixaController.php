<?php
namespace App\Controllers;

use App\Models\RelatorioMovimentoCaixa;

class RelatorioMovimentoCaixaController
{
    public function index()
    {
        $dataInicial = $_GET['data_inicial'] ?? null;
        $dataFinal = $_GET['data_final'] ?? null;

        $saldoAnterior = 0;
        $movimentacoes = [];
        $totais = ['entradas' => 0, 'saidas' => 0];

        if ($dataInicial && $dataFinal) {
            $saldoAnterior = RelatorioMovimentoCaixa::getSaldoAnterior($dataInicial);
            $movimentacoes = RelatorioMovimentoCaixa::getMovimentacoes($dataInicial, $dataFinal);
            $totais = RelatorioMovimentoCaixa::getTotais($dataInicial, $dataFinal);
        }

        require __DIR__ . '/../Views/relatorioMovimentoCaixa/index.php';
    }
}
