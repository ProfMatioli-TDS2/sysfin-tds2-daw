<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Usuario
{
    public $id;
    public $nome;
    public $login;
    public $senha; // Apenas para o formulário
    public $senha_hash; // O que vai para o banco
    public $ativo;
    public $perfis = []; // Array de IDs de perfil (do formulário)

    // (Para corrigir avisos 'Deprecated')
    public $perfis_nomes; 
    public $id_perfil; 
    public $usuario_id; 

    /**
     * Busca um usuário ATIVO pelo seu 'login'.
     */
    public static function findByLogin(string $login)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE login = :login AND ativo = 1');
        $stmt->execute(['login' => $login]);
        return $stmt->fetchObject(self::class);
    }

    /**
     * Busca os nomes dos perfis de um usuário.
     */
    public static function getPerfis(int $usuarioId): array
    {
        $pdo = Database::getConnection();
        
        $sql = "SELECT p.nome 
                FROM perfis p
                INNER JOIN usuario_perfis up ON p.id = up.id_perfil
                WHERE up.id_usuario = :id";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    /**
     * Busca TODOS os usuários e seus perfis (para a listagem)
     */
    public static function getAll()
    {
        $pdo = Database::getConnection();
        
        $sql = "SELECT u.id, u.nome, u.login, u.ativo, 
                       GROUP_CONCAT(p.nome SEPARATOR ', ') as perfis_nomes
                FROM usuarios u
                LEFT JOIN usuario_perfis up ON u.id = up.id_usuario
                LEFT JOIN perfis p ON up.id_perfil = p.id
                GROUP BY u.id
                ORDER BY u.nome";
        
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Busca um usuário pelo ID (para o formulário de edição)
     */
    public static function getById(int $id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject(self::class);
    }

    /**
     * Busca apenas os IDs dos perfis de um usuário (para o form.php)
     */
    public function getProfileIds(): array
    {
        if (!$this->id) return [];
        $pdo = Database::getConnection();
        
        $stmt = $pdo->prepare('SELECT id_perfil FROM usuario_perfis WHERE id_usuario = :id');
        $stmt->execute(['id' => $this->id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
    }

    /**
     * Exclui um usuário (e suas associações de perfil)
     */
    public static function delete(int $id)
    {
        $pdo = Database::getConnection();
        try {
            $pdo->beginTransaction();
            $pdo->prepare('DELETE FROM usuario_perfis WHERE id_usuario = :id')->execute(['id' => $id]);
            $pdo->prepare('DELETE FROM usuarios WHERE id = :id')->execute(['id' => $id]);
            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }


    /**
     * Salva (Cria ou Atualiza) um usuário e seus perfis
     */
    public function save(): bool
    {
        $pdo = Database::getConnection();
        
        try {
            $pdo->beginTransaction();

            // --- 1. Salva o Usuário ---
            
            if (!empty($this->senha)) {
                $this->senha_hash = password_hash($this->senha, PASSWORD_DEFAULT);
            }

            if ($this->id) {
                // ATUALIZAR
                if ($this->senha_hash) {
                    $sql = "UPDATE usuarios SET nome = :nome, login = :login, ativo = :ativo, senha_hash = :hash WHERE id = :id";
                    $params = [
                        'nome' => $this->nome,
                        'login' => $this->login,
                        'ativo' => $this->ativo,
                        'hash' => $this->senha_hash,
                        'id' => $this->id
                    ];
                } else {
                    $sql = "UPDATE usuarios SET nome = :nome, login = :login, ativo = :ativo WHERE id = :id";
                    $params = [
                        'nome' => $this->nome,
                        'login' => $this->login,
                        'ativo' => $this->ativo,
                        'id' => $this->id
                    ];
                }
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                
            } else {
                // CRIAR
                if (empty($this->senha_hash)) {
                    throw new Exception("A senha é obrigatória ao criar um usuário.");
                }
                
                $sql = "INSERT INTO usuarios (nome, login, ativo, senha_hash) VALUES (:nome, :login, :ativo, :hash)";
                
                // ==========================================================
                // ERRO CORRIGIDO (Linha 152)
                // Era $this.nome, $this.login, etc.
                // ==========================================================
                $params = [
                    'nome' => $this->nome,
                    'login' => $this->login,
                    'ativo' => $this->ativo,
                    'hash' => $this->senha_hash
                ];
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $this->id = $pdo->lastInsertId();
            }
            
            // --- 2. Salva os Perfis ---

            $stmtDel = $pdo->prepare('DELETE FROM usuario_perfis WHERE id_usuario = :id');
            $stmtDel->execute(['id' => $this->id]);
            
            if (empty($this->perfis)) {
                // Resolve o erro "1364 Field 'id_perfil' doesn't have a default value"
                throw new Exception("Você deve selecionar pelo menos um perfil de acesso.");
            }
            
            $stmtIns = $pdo->prepare('INSERT INTO usuario_perfis (id_usuario, id_perfil) VALUES (:uid, :pid)');
            foreach ($this->perfis as $perfil_id) {
                $stmtIns->execute(['uid' => $this->id, 'pid' => $perfil_id]);
            }
            
            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e; // Lança o erro para o Controller
        }
    }
}