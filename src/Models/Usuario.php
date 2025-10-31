<?php
namespace App\Models;

use \PDO;
use \PDOException;

class Usuario
{
    private $conn;

    /**
     * O construtor agora recebe a conexão PDO do Database::getConnection()
     * e a armazena na propriedade $conn.
     */
    public function __construct(PDO $db_connection)
    {
        $this->conn = $db_connection;
    }

    /**
     * Busca um usuário pelo seu email.
     * @param string $email
     * @return object|false
     */
    public function findByEmail(string $email)
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário por email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca os nomes dos perfis de um usuário (conforme PDF).
     * @param int $id_usuario
     * @return array Lista de nomes de perfis (ex: ['Administrador', 'Vendedor'])
     */
    public function findPerfis(int $id_usuario): array
    {
        try {
            $sql = "SELECT p.nome 
                    FROM perfis p
                    JOIN usuarios_perfis up ON p.id = up.id_perfil
                    WHERE up.id_usuario = :id_usuario";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_usuario' => $id_usuario]);
            
            // Retorna um array simples com os nomes
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Erro ao buscar perfis do usuário: " . $e->getMessage());
            return [];
        }
    }

    /**
     * @param string $nome
     * @param string $email
     * @param string $senhaPlana 
     * @param array $perfis_ids 
     * @return bool
     */
    public function create(string $nome, string $email, string $senhaPlana, array $perfis_ids): bool
    {
        $senha_hash = password_hash($senhaPlana, PASSWORD_DEFAULT);

        $this->conn->beginTransaction();
        try {
            // 1. Inserir o usuário
            $sqlUser = "INSERT INTO usuarios (nome, email, senha_hash) VALUES (:nome, :email, :senha_hash)";
            $stmtUser = $this->conn->prepare($sqlUser);
            $stmtUser->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha_hash' => $senha_hash
            ]);

            $idUsuario = $this->conn->lastInsertId();

            $sqlPerfis = "INSERT INTO usuarios_perfis (id_usuario, id_perfil) VALUES (:id_usuario, :id_perfil)";
            $stmtPerfis = $this->conn->prepare($sqlPerfis);

            foreach ($perfis_ids as $id_perfil) {
                $stmtPerfis->execute([
                    ':id_usuario' => $idUsuario,
                    ':id_perfil'  => (int) $id_perfil
                ]);
            }

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return false;
        }
    }
}