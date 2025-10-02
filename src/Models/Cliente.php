<?php
// App/Models/Cliente.php 
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
        $stmt = $pdo->query('SELECT * FROM clientes ORDER BY nome');
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }
    public static function getById($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM clientes WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject(self::class);
    }
    public function save()
    {
        $pdo = Database::getConnection();
        if ($this->id) {
            // Atualizar 
            $stmt = $pdo->prepare(
                'UPDATE clientes SET nome = :nome, cpf_cnpj = :cpf_cnpj, 
email = :email, telefone = :telefone WHERE id = :id'
            );
            $stmt->execute([
                'id' => $this->id,
                'nome' => $this->nome,
                'cpf_cnpj' => $this->cpf_cnpj,
                'email' => $this->email,
                'telefone' => $this->telefone
            ]);
        } else {
            // Inserir 
            $stmt = $pdo->prepare(
                'INSERT INTO clientes (nome, cpf_cnpj, email, telefone) 
VALUES (:nome, :cpf_cnpj, :email, :telefone)'
            );
            $stmt->execute([
                'nome' => $this->nome,
                'cpf_cnpj' => $this->cpf_cnpj,
                'email' => $this->email,
                'telefone' => $this->telefone
            ]);
            $this->id = $pdo->lastInsertId();
        }
        return $stmt->rowCount() > 0;
    }
    public static function delete($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM clientes WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }
}