<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Perfil
{
    public $id;
    public $nome;

    public static function getAll()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT * FROM perfis ORDER BY nome');
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function getById(int $id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM perfis WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject(self::class);
    }

    public function save(): bool
    {
        $pdo = Database::getConnection();
        if ($this->id) {
            $stmt = $pdo->prepare('UPDATE perfis SET nome = :nome WHERE id = :id');
            return $stmt->execute(['nome' => $this->nome, 'id' => $this->id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO perfis (nome) VALUES (:nome)');
            return $stmt->execute(['nome' => $this->nome]);
        }
    }

    /**
     * Verifica se um perfil está em uso por algum usuário.
     * (CORREÇÃO: 'perfil_id' -> 'id_perfil')
     */
    public static function isProfileInUse(int $id): bool
    {
        $pdo = Database::getConnection();
        // CORRIGIDO AQUI
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM usuario_perfis WHERE id_perfil = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn() > 0;
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM perfis WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}