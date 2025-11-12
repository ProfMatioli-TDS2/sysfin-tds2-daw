<?php
namespace App\Controllers;

use App\Models\Produto; // Importa o Model de Produto

class RelatorioEstoqueController
{
    /**
     * Exibe o Relatório de Estoque.
     */
    public function index()
    {
        // 1. Busca todos os produtos (o Model já existe)
        // O PDF indica que a tabela 'produtos' tem 'estoque_atual' e 'estoque_minimo'
        $produtos = Produto::getAll();
        
        // 2. Passa os dados para a View
        extract(['produtos' => $produtos]);
        
        // 3. Carrega a View (que vamos criar a seguir)
        require __DIR__ . '/../Views/relatorio-estoque/index.php';
    }
}