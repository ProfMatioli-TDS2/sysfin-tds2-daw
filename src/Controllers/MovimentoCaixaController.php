<?php
namespace App\Controllers;

// 1. IMPORTA O SESSION MANAGER
use App\Core\SessionManager;
use App\Core\Database;
use App\Models\MovimentoCaixaModel;

class MovimentoCaixaController
{
    public function index()
    {
        // 2. PROTEÇÃO DO MÉTODO
        // Exige que o usuário esteja logado (qualquer perfil)
        SessionManager::require_auth();
        
        // obtém conexão (ajuste para seu método atual)
        $db = Database::getConnection();

        // cria o model
        $model = new MovimentoCaixaModel($db);

        // Busca Saldo Atual
        $saldoatual = $model->getSaldoAtualEmCaixa();

        /// Busca o número de produtos com estoque baixo
        $estoqueBaixo = $model->getProdutosComEstoqueBaixo();

        // Busca os últimos 5 lançamentos
        $ultimosLancamentos = $model->getUltimosLancamentos();
        
        // [NOVO] Busca o total vendido hoje
        $totalVendidoHoje = $model->getTotalVendidoHoje();
            

        // carrega a view e passa os dados
        require __DIR__ . '/../Views/home/index.php';
    }
}