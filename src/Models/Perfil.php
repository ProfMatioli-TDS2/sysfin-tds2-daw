<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Perfil
{
    public $id;
    public $nome;

    /**
     * Busca todos os perfis.
     */
    public static function getAll()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT * FROM perfis ORDER BY nome');
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Busca um perfil pelo ID.
     */
    public static function getById(int $id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM perfis WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject(self::class);
    }

    /**
     * Salva (cria ou atualiza) um perfil.
     */
    public function save(): bool
    {
        $pdo = Database::getConnection();
        if ($this->id) {
            // Atualizar
            $stmt = $pdo->prepare('UPDATE perfis SET nome = :nome WHERE id = :id');
            return $stmt->execute(['nome' => $this->nome, 'id' => $this->id]);
        } else {
            // Criar
            $stmt = $pdo->prepare('INSERT INTO perfis (nome) VALUES (:nome)');
            return $stmt->execute(['nome' => $this->nome]);
        }
    }

    /**
     * Verifica se um perfil está em uso por algum usuário.
     * (Requisito de Segurança da Exclusão)
     */
    public static function isProfileInUse(int $id): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM usuario_perfis WHERE perfil_id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Exclui um perfil.
     */
    public static function delete(int $id): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM perfis WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}