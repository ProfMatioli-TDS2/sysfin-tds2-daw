<?php

namespace App\Controllers;

use App\Core\Database;
use App\Models\Estoque;

class EstoqueController
{
    public function index()
    {
        $db = Database::getConnection();

        $estoqueModel = new Estoque($db);
        $produtos = $estoqueModel->getProdutos();

        require __DIR__ . '/../Views/estoque/index.php';
    }
}
