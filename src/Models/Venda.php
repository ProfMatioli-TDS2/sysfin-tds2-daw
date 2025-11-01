<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Venda
{
    public $id;
    public $id_cliente;
    public $data_venda;
    public $valor_total;
    public $itens = [];

    public function save()
    {
        $pdo = Database::getConnection();

        try {
            $pdo->beginTransaction();

            $stmtVenda = $pdo->prepare(
                "INSERT INTO vendas (id_cliente, data_venda, valor_total) VALUES (?, NOW(), ?)"
            );
            $stmtVenda->execute([$this->id_cliente, $this->valor_total]);
            $this->id = $pdo->lastInsertId();

            $stmtItem = $pdo->prepare(
                "INSERT INTO itens_venda (id_venda, id_produto, quantidade, valor_unitario) VALUES (?, ?, ?, ?)"
            );
            $stmtEstoque = $pdo->prepare(
                "UPDATE produtos SET estoque_atual = estoque_atual - ? WHERE id = ?"
            );

            foreach ($this->itens as $item) {
                $stmtItem->execute([
                    $this->id,
                    $item['id_produto'],
                    $item['quantidade'],
                    $item['valor_unitario']
                ]);

                $stmtEstoque->execute([
                    $item['quantidade'],
                    $item['id_produto']
                ]);
            }

            $stmtCaixa = $pdo->prepare(
                "INSERT INTO movimento_caixa (data_movimento, descricao, id_plano_de_contas, tipo, valor, id_venda) VALUES (NOW(), ?, ?, 'E', ?, ?)"
            );
            $stmtCaixa->execute([
                "Receita da Venda #" . $this->id,
                1,
                $this->valor_total,
                $this->id
            ]);

            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            throw new Exception("Falha ao registrar a venda: " . $e->getMessage());
        }
    }

    public static function getAll()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("
            SELECT v.id, v.data_venda, c.nome AS nome_cliente, v.valor_total
            FROM vendas v
            JOIN clientes c ON v.id_cliente = c.id
            ORDER BY v.data_venda DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT v.id, v.data_venda, v.id_cliente, c.nome AS nome_cliente, v.valor_total
            FROM vendas v
            JOIN clientes c ON v.id_cliente = c.id
            WHERE v.id = ?
        ");
        $stmt->execute([$id]);
        $venda = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($venda) {
            $stmtItens = $pdo->prepare("
                SELECT iv.id_produto, p.nome AS nome_produto, iv.quantidade, iv.valor_unitario
                FROM itens_venda iv
                JOIN produtos p ON iv.id_produto = p.id
                WHERE iv.id_venda = ?
            ");
            $stmtItens->execute([$id]);
            $venda['itens'] = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
        }

        return $venda;
    }
}
