<?php
namespace App\Models;

use App\Database;
use PDO;

class RelatorioVendas
{
    public static function getByPeriodo($dataInicio, $dataFim)
    {
        $pdo = Database::getConnection();
        $sql = "SELECT v.id, v.data_venda, c.nome AS cliente, v.valor_total
                FROM vendas v
                JOIN clientes c ON c.id = v.id_cliente
                WHERE v.data_venda BETWEEN :inicio AND :fim
                ORDER BY v.data_venda ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['inicio' => $dataInicio, 'fim' => $dataFim]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
