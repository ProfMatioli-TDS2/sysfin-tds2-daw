<?php
// App/Models/Estoque.php

namespace App\Models;

use PDO;

class Estoque
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getProdutos()
    {
        $query = "SELECT id, nome, preco_venda, estoque_atual FROM produtos";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
