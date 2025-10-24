<?php
namespace App\Models;

use App\Database;
use PDO;

class Venda
{
    public static function getByPeriodo($inicio, $fim)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT v.id, c.nome AS cliente, v.data_venda, v.valor_total
            FROM vendas v
            JOIN clientes c ON v.id_cliente = c.id
            WHERE v.data_venda BETWEEN :inicio AND :fim
            ORDER BY v.data_venda ASC
        ");
        $stmt->execute(['inicio' => $inicio, 'fim' => $fim]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
