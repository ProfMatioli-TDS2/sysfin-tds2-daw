<?php
namespace App\Controllers;

use App\Core\SessionManager;

class AuthController
{
    /**
     * Exibe a tela de login (GET)
     */
    public function login()
    {
        // Se já estiver logado, redireciona para a home
        if (SessionManager::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/index.php?url=/');
            exit;
        }

        // Passa variáveis de erro (se houver) para a view
        $error = $_GET['error'] ?? null;
        require __DIR__ . '/../Views/login.php';
    }

    /**
     * Processa a tentativa de login (POST)
     */
    public function auth()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? null;
            $senha = $_POST['senha'] ?? null;

            if ($login && $senha && SessionManager::login($login, $senha)) {
                // Sucesso! Redireciona para a home
                header('Location: ' . BASE_URL . '/index.php?url=/');
                exit;
            } else {
                // Falha! Redireciona de volta para o login com erro
                $errorMsg = "Login ou senha inválidos.";
                header('Location: ' . BASE_URL . '/index.php?url=/login&error=' . urlencode($errorMsg));
                exit;
            }
        }
        // Se não for POST, apenas redireciona para o login
        header('Location: ' . BASE_URL . '/index.php?url=/login');
    }

    /**
     * Processa o logout
     */
    public function logout()
    {
        SessionManager::logout();
        header('Location: ' . BASE_URL . '/index.php?url=/login');
        exit;
    }
}