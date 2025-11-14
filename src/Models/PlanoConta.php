<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class PlanoConta
{
    public $id;
    public $descricao;
    public $tipo; // 'R' para Receita, 'D' para Despesa

    /**
     * Retorna todas as contas, ordenadas por tipo e descrição.
     * (Requisito da Tarefa)
     */
    public static function getAll(): mixed
    {
        $pdo = Database::getConnection();
        // Ordena por Tipo (Despesas 'D' vem antes de Receitas 'R') e Descrição
        $stmt = $pdo->query("SELECT * FROM plano_de_contas ORDER BY tipo, descricao");
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Retorna uma conta específica pelo ID.
     */
    public static function getById(int $id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM plano_de_contas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject(self::class);
    }

    /**
     * Salva a conta (Nova ou Edição).
     */
    public function save(): bool
    {
        $pdo = Database::getConnection();

        if ($this->id) {
            // Atualização
            $stmt = $pdo->prepare(
                "UPDATE plano_de_contas SET descricao = :descricao, tipo = :tipo WHERE id = :id"
            );
            $params = [
                'id' => $this->id,
                'descricao' => $this->descricao,
                'tipo' => $this->tipo
            ];
        } else {
            // Inserção
            $stmt = $pdo->prepare(
                "INSERT INTO plano_de_contas (descricao, tipo) VALUES (:descricao, :tipo)"
            );
            $params = [
                'descricao' => $this->descricao,
                'tipo' => $this->tipo
            ];
        }

        return $stmt->execute($params);
    }

    /**
     * Exclui uma conta.
     */
    public static function delete(int $id): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM plano_de_contas WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * VERIFICAÇÃO DE SEGURANÇA (Requisito da Tarefa)
     * Verifica se a conta é uma das 5 contas padrão (IDs 1 a 5, conforme o PDF).
     */
    public static function isProtegido(int $id): bool
    {
        return ($id >= 1 && $id <= 5);
    }

    /**
     * VERIFICAÇÃO DE SEGURANÇA (Requisito da Tarefa)
     * Verifica se a conta tem algum lançamento na tabela 'movimento_caixa'.
     */
    public static function hasMovimentacoes(int $id): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM movimento_caixa WHERE id_plano_de_contas = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn() > 0;
    }
}