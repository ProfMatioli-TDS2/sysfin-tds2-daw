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
    public $senha; 
    public $senha_hash; 
    public $ativo;
    public $perfis = []; 

    public $perfis_nomes; 
    public $id_perfil; 
    public $usuario_id; 

    // ... (Métodos de busca findByLogin, getPerfis, getAll, getById, getProfileIds mantidos iguais) ...
    public static function findByLogin(string $login) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE login = :login AND ativo = 1');
        $stmt->execute(['login' => $login]);
        return $stmt->fetchObject(self::class);
    }
    public static function getPerfis(int $usuarioId): array {
        $pdo = Database::getConnection();
        $sql = "SELECT p.nome FROM perfis p INNER JOIN usuario_perfis up ON p.id = up.id_perfil WHERE up.id_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    public static function getAll() {
        $pdo = Database::getConnection();
        // Nota: Ajustado para pegar o id_perfil direto da tabela usuarios se necessário, mas mantendo a lógica original
        $sql = "SELECT u.id, u.nome, u.login, u.ativo, GROUP_CONCAT(p.nome SEPARATOR ', ') as perfis_nomes FROM usuarios u LEFT JOIN usuario_perfis up ON u.id = up.id_usuario LEFT JOIN perfis p ON up.id_perfil = p.id GROUP BY u.id ORDER BY u.nome";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }
    public static function getById(int $id) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject(self::class);
    }
    public function getProfileIds(): array {
        if (!$this->id) return [];
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id_perfil FROM usuario_perfis WHERE id_usuario = :id');
        $stmt->execute(['id' => $this->id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0); 
    }
    public static function delete(int $id) {
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
     * Salva (Cria ou Atualiza)
     */
    public function save(): bool
    {
        $pdo = Database::getConnection();
        
        try {
            // 1. PREPARAÇÃO DOS PERFIS (Antes de tudo!)
            // Limpa o array de perfis para garantir que temos dados válidos
            $perfisLimpos = array_filter($this->perfis ?? []);

            if (empty($perfisLimpos)) {
                throw new Exception("Você deve selecionar pelo menos um perfil de acesso.");
            }

            // Pega o primeiro perfil para salvar na coluna principal 'id_perfil' da tabela 'usuarios'
            // Isso resolve o erro 1364 na tabela de usuários.
            $idPerfilPrincipal = (int) reset($perfisLimpos);

            $pdo->beginTransaction();

            // --- 2. Salva o Usuário (Tabela 'usuarios') ---
            
            if (!empty($this->senha)) {
                $this->senha_hash = password_hash($this->senha, PASSWORD_DEFAULT);
            }

            if ($this->id) {
                // UPDATE
                // Agora incluímos o id_perfil na atualização
                if ($this->senha_hash) {
                    $sql = "UPDATE usuarios SET nome = ?, login = ?, ativo = ?, senha_hash = ?, id_perfil = ? WHERE id = ?";
                    $params = [$this->nome, $this->login, $this->ativo, $this->senha_hash, $idPerfilPrincipal, $this->id];
                } else {
                    $sql = "UPDATE usuarios SET nome = ?, login = ?, ativo = ?, id_perfil = ? WHERE id = ?";
                    $params = [$this->nome, $this->login, $this->ativo, $idPerfilPrincipal, $this->id];
                }
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                
            } else {
                // INSERT
                // Agora incluímos o id_perfil na criação (Resolvendo o erro 1364)
                if (empty($this->senha_hash)) {
                    throw new Exception("A senha é obrigatória ao criar um usuário.");
                }
                
                $sql = "INSERT INTO usuarios (nome, login, ativo, senha_hash, id_perfil) VALUES (?, ?, ?, ?, ?)";
                $params = [$this->nome, $this->login, $this->ativo, $this->senha_hash, $idPerfilPrincipal];
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $this->id = $pdo->lastInsertId();
            }
            
            // --- 3. Salva os Perfis (Tabela N:N 'usuario_perfis') ---
            // Mantemos isso pois seu sistema parece usar checkbox (múltiplos perfis)
            
            $stmtDel = $pdo->prepare('DELETE FROM usuario_perfis WHERE id_usuario = ?');
            $stmtDel->execute([$this->id]);
            
            $stmtIns = $pdo->prepare('INSERT INTO usuario_perfis (id_usuario, id_perfil) VALUES (?, ?)');
            
            foreach ($perfisLimpos as $perfil_id) {
                $stmtIns->execute([$this->id, (int)$perfil_id]);
            }
            
            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e; 
        }
    }
}