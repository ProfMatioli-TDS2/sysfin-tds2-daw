<?php
namespace App\Core;

use App\Models\Usuario; 

class SessionManager
{
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            // Configurações de segurança da sessão
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_strict_mode', 1);
            session_start();
        }
    }

    /**
     * @param string $email
     * @param string $senha
     * @return bool 
     */
    public static function login(string $email, string $senha): bool
    {
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->findByEmail($email);

        if ($usuario && password_verify($senha, $usuario->senha_hash)) {
            $perfis = $usuarioModel->findPerfis($usuario->id);

            session_regenerate_id(true);


            $_SESSION['user'] = [
                'id'    => $usuario->id,
                'nome'  => $usuario->nome,
                'email' => $usuario->email,
                'perfis' => $perfis 
            ];
            
            return true;
        }

        return false;
    }


    public static function logout()
    {
        self::start();
        session_unset();
        session_destroy();
    }

    /**
     * @return bool
     */
    public static function isLoggedIn(): bool
    {
        self::start();
        return isset($_SESSION['user']['id']);
    }

    /**
     * @return array|null
     */
    public static function getUser(): ?array
    {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    /**
     * @return int|null
     */
    public static function getUserId(): ?int
    {
        self::start();
        return $_SESSION['user']['id'] ?? null;
    }

    /**
     * @param string $perfilNome
     * @return bool
     */
    public static function hasRole(string $perfilNome): bool
    {
        $user = self::getUser();
        if (!$user) {
            return false;
        }
        return in_array($perfilNome, $user['perfis']);
    }
}
