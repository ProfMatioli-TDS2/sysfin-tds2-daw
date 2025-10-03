<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Produto
{
    public $id;
    public $nome;
    public $descricao;
    public $preco_venda;
    public $estoque;

    /**
     * Retorna todos os produtos do banco de dados.
     */
    public static function getAll()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT * FROM produtos ORDER BY nome');
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Busca um produto pelo seu ID.
     */
    public static function getById($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM produtos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject(self::class);
    }

    /**
     * Salva o produto (novo ou existente) no banco de dados.
     */
    public function save()
    {
        $pdo = Database::getConnection();

        if ($this->id) {
            // --- ATUALIZAR UM PRODUTO EXISTENTE ---
            // Note que o estoque não é atualizado aqui, conforme a regra de negócio.
            $stmt = $pdo->prepare(
                'UPDATE produtos SET nome = :nome, descricao = :descricao, preco_venda = :preco_venda WHERE id = :id'
            );
            $stmt->execute([
                'id' => $this->id,
                'nome' => $this->nome,
                'descricao' => $this->descricao,
                'preco_venda' => $this->preco_venda
            ]);
        } else {
            // --- INSERIR UM NOVO PRODUTO ---
            $stmt = $pdo->prepare(
                'INSERT INTO produtos (nome, descricao, preco_venda, estoque) VALUES (:nome, :descricao, :preco_venda, :estoque)'
            );
            $stmt->execute([
                'nome' => $this->nome,
                'descricao' => $this->descricao,
                'preco_venda' => $this->preco_venda,
                'estoque' => $this->estoque
            ]);
            $this->id = $pdo->lastInsertId();
        }
        return $stmt->rowCount() > 0;
    }

    /**
     * Exclui um produto do banco de dados.
     */
    public static function delete($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM produtos WHERE id = :id');
        // Adicionar verificação se o produto foi usado em compras/vendas antes de excluir.
        // (Isso será implementado futuramente)
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Busca produtos por nome.
     */
    public static function searchByName($nome)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE nome LIKE :nome ORDER BY nome");
        $stmt->execute(['nome' => '%' . $nome . '%']);
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }
}