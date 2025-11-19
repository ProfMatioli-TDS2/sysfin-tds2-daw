<?php
namespace App\Core;

use App\Models\Usuario; 

class SessionManager
{
    /**
     * Inicia a sessão de forma segura.
     */
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_strict_mode', 1);
            session_start();
        }
    }

    /**
     * Valida o login, busca o usuário e seus perfis, e cria a sessão.
     * @param string $login (O 'login' do banco)
     * @param string $senha
     * @return bool 
     */
    public static function login(string $login, string $senha): bool
    {
        // --- INÍCIO DA CORREÇÃO ---
        // Faltava iniciar a sessão ANTES de tentar salvar nela.
        self::start();
        // --- FIM DA CORREÇÃO ---

        // 1. Busca o usuário pelo 'login' (usando o método do seu Model)
        $usuario = Usuario::findByLogin($login);

        // 2. Verifica a senha e se o usuário está ativo
        if ($usuario && password_verify($senha, $usuario->senha_hash)) {
            
            // 3. Busca os perfis
            $perfis = Usuario::getPerfis($usuario->id);

            // Regenera o ID da sessão
            session_regenerate_id(true);

            // 4. Salva as informações essenciais na sessão
            $_SESSION['user'] = [
                'id'     => $usuario->id,
                'nome'   => $usuario->nome,
                'login'  => $usuario->login,
                'perfis' => $perfis // Salva o array de nomes (ex: ["Administrador"])
            ];
            
            return true;
        }

        return false;
    }

    /**
     * Destrói a sessão do usuário (Logout).
     */
    public static function logout()
    {
        self::start();
        session_unset();
        session_destroy();
    }

    /**
     * Verifica se o usuário está logado.
     */
    public static function isLoggedIn(): bool
    {
        self::start();
        return isset($_SESSION['user']['id']);
    }

    /**
     * Retorna o array do usuário da sessão.
     */
    public static function getUser(): ?array
    {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    /**
     * Retorna o ID do usuário logado.
     */
    public static function getUserId(): ?int
    {
        self::start();
        return $_SESSION['user']['id'] ?? null;
    }

    /**
     * Verifica se o usuário logado possui um perfil (role) específico.
     * @param string $perfilNome (ex: "Administrador")
     */
    public static function hasRole(string $perfilNome): bool
    {
        $user = self::getUser();
        if (!$user) {
            return false;
        }
        return in_array($perfilNome, $user['perfis']);
    }

    /**
     * O "Porteiro" do sistema.
     * @param array $perfisRequeridos (ex: ['Administrador', 'Tesoureiro'])
     */
    public static function require_auth(array $perfisRequeridos = [])
    {
        self::start();

        // 1. Se não estiver logado, redireciona para o login
        if (!self::isLoggedIn()) {
            
            // Usa a URL base e a estrutura ?url=
            header('Location: ' . BASE_URL . '/index.php?url=/login');
            exit;
        }

        // 2. Se a rota exige perfis, verifica se o usuário tem
        if (!empty($perfisRequeridos)) {
            $acessoPermitido = false;
            foreach ($perfisRequeridos as $perfil) {
                if (self::hasRole($perfil)) {
                    $acessoPermitido = true;
                    break;
                }
            }

            // 3. Se não tiver permissão, exibe erro 403 (Proibido)
            if (!$acessoPermitido) {
                http_response_code(403);
                echo "<h1>403 - Acesso Negado</h1>";
                echo "<p>Você não tem permissão para acessar esta página.</p>";
                exit;
            }
        }
    }
}