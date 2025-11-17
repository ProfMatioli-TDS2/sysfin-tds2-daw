<?php
namespace App\Controllers;

// 1. IMPORTA O SESSION MANAGER
use App\Core\SessionManager;
use App\Core\Database;
use App\Models\MovimentoCaixaModel;

class RelatorioCaixaController
{
    /**
     * Exibe o filtro (GET) ou o relatório (POST)
     */
    public function index()
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro']);
        
        // Dados padrão para a view (quando a página é carregada)
        $data = [
            'dataInicial' => '',
            'dataFinal' => '',
            'saldoAnterior' => 0.0,
            'movimentacoes' => [],
            'totalEntradas' => 0.0,
            'totalSaidas' => 0.0,
            'saldoFinal' => 0.0,
            'hasData' => false // Controla se o relatório deve ser exibido
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dataInicial = $_POST['data_inicial'] ?? date('Y-m-d');
            $dataFinal = $_POST['data_final'] ?? date('Y-m-d');
            
            // 1. REQUISITO #2a: Buscar Saldo Anterior
            $saldoAnterior = MovimentoCaixa::getSaldoAnterior($dataInicial);
            
            // 2. REQUISITO #2b: Buscar Movimentações no Período
            $movimentacoes = MovimentoCaixa::getMovimentacoesPorPeriodo($dataInicial, $dataFinal);
            
            // 3. REQUISITO #2c: Calcular Totais do Período
            $totalEntradas = 0.0;
            $totalSaidas = 0.0;
            foreach ($movimentacoes as $mov) {
                if ($mov['tipo'] == 'E') {
                    $totalEntradas += (float) $mov['valor'];
                } else {
                    $totalSaidas += (float) $mov['valor'];
                }
            }
            
            // 4. REQUISITO #2c: Calcular Saldo Final
            $saldoFinal = $saldoAnterior + $totalEntradas - $totalSaidas;
            
            // Preenche $data com os resultados
            $data = [
                'dataInicial' => $dataInicial,
                'dataFinal' => $dataFinal,
                'saldoAnterior' => $saldoAnterior,
                'movimentacoes' => $movimentacoes,
                'totalEntradas' => $totalEntradas,
                'totalSaidas' => $totalSaidas,
                'saldoFinal' => $saldoFinal,
                'hasData' => true // Mostra o relatório
            ];
        }

        // 5. Envia os dados para a View
        extract($data);
        require __DIR__ . '/../Views/relatorio-caixa/index.php';
    }
}