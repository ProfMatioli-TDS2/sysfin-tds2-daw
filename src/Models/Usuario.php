<?php
namespace App\Models;

use \PDO;
use \PDOException;

class Usuario
{
    private $conn;

    // Conexão com o banco (igual aos seus outros models)
    public function __construct()
    {
        $host = '143.106.241.4'; 
        $dbname = 'matioli';     
        $user = 'bancomatioli'; 
        $pass = 'senhabanco'; 
        
        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
        }
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
            $sqlUser = "INSERT INTO usuarios (nome, email, senha_hash) VALUES (:nome, :email, :senha_hash)";
            $stmtUser = $this->conn->prepare($sqlUser);
            $stmtUser->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha_hash' => $senha_hash
            ]);

            $idUsuario = $this->conn->lastInsertId();

            // 2. Inserir os perfis na tabela pivot (usuarios_perfis)
            $sqlPerfis = "INSERT INTO usuarios_perfis (id_usuario, id_perfil) VALUES (:id_usuario, :id_perfil)";
            $stmtPerfis = $this->conn->prepare($sqlPerfis);

            foreach ($perfis_ids as $id_perfil) {
                $stmtPerfis->execute([
                    ':id_usuario' => $idUsuario,
                    ':id_perfil'  => (int) $id_perfil
                ]);
            }

            // 3. Confirmar a transação
            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            // 4. Desfazer tudo em caso de erro
            $this->conn->rollBack();
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return false;
        }
    }
}

