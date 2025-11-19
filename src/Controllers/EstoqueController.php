<?php

namespace App\Controllers;

// 1. IMPORTA O SESSION MANAGER
use App\Core\SessionManager;
use App\Core\Database;
use App\Models\Estoque;

class EstoqueController
{
    public function index()
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro', 'Vendedor']);
        
        $db = Database::getConnection();

        $estoqueModel = new Estoque($db);
        $produtos = $estoqueModel->getProdutos();

        require __DIR__ . '/../Views/estoque/index.php';
    }
}