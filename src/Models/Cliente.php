<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Cliente
{
    public $id;
    public $nome;
    public $cpf_cnpj; 
    public $email;
    public $telefone;

    public static function getAll()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM clientes ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function getById($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }

    public function save()
    {
        $pdo = Database::getConnection();

        if ($this->id) {
            $stmt = $pdo->prepare("UPDATE clientes SET nome = ?, cpf_cnpj = ?, email = ?, telefone = ? WHERE id = ?");
            $stmt->execute([$this->nome, $this->cpf_cnpj, $this->email, $this->telefone, $this->id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO clientes (nome, cpf_cnpj, email, telefone) VALUES (?, ?, ?, ?)");
            $stmt->execute([$this->nome, $this->cpf_cnpj, $this->email, $this->telefone]);
        }
    }

    public static function delete($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function searchByName($nome)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE nome LIKE ?");
        $stmt->execute(['%' . $nome . '%']);
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }
}