<?php
namespace App\Controllers;

use App\Core\SessionManager;
use App\Models\Usuario;
use App\Models\Perfil;
use Exception;

class UserController
{
    public function index()
    {
        SessionManager::require_auth(['Administrador']);
        $usuarios = Usuario::getAll();
        $error = $_GET['error'] ?? null;
        require __DIR__ . '/../Views/users/index.php';
    }

    public function create()
    {
        SessionManager::require_auth(['Administrador']);
        
        $perfisDisponiveis = Perfil::getAll();
        $error = null;
        $usuario = new Usuario(); 
        $perfisDoUsuario = []; // (Para o form.php)

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->nome = $_POST['nome'] ?? '';
            $usuario->login = $_POST['login'] ?? '';
            $usuario->senha = $_POST['senha'] ?? '';
            $usuario->ativo = isset($_POST['ativo']) ? 1 : 0;

            // --- INÍCIO DA CORREÇÃO ---
            // 1. Limpa o array de perfis aqui, removendo valores vazios (como "")
            // A função array_filter() sem segundo argumento remove null, false, "" e 0.
            $perfisPostados = array_filter($_POST['perfis'] ?? []);
            // --- FIM DA CORREÇÃO ---

            $usuario->perfis = $perfisPostados; // Passa o array limpo para o Model
            $perfisDoUsuario = $perfisPostados; // Passa o array limpo para a View (repopular)
            
            try {
                // O Model (save) agora recebe um array 100% limpo
                $usuario->save();
                header('Location: ' . BASE_URL . '/index.php?url=/users');
                exit;
            } catch (Exception $e) {
                // Captura erros (ex: "Você deve selecionar...")
                $error = $e->getMessage();
            }
        }
        
        // Passa os dados para a view
        extract(['usuario' => $usuario, 'perfisDisponiveis' => $perfisDisponiveis, 'error' => $error, 'perfisDoUsuario' => $perfisDoUsuario]);
        require __DIR__ . '/../Views/users/criar.php';
    }

    public function edit($id)
    {
        SessionManager::require_auth(['Administrador']);
        
        $usuario = Usuario::getById($id);
        if (!$usuario) {
            header('Location: ' . BASE_URL . '/index.php?url=/users');
            exit;
        }

        $perfisDisponiveis = Perfil::getAll();
        $perfisDoUsuario = $usuario->getProfileIds(); // Pega do banco
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->nome = $_POST['nome'];
            $usuario->login = $_POST['login'];
            $usuario->senha = $_POST['senha']; 
            $usuario->ativo = isset($_POST['ativo']) ? 1 : 0;
            
            // --- INÍCIO DA CORREÇÃO ---
            // 1. Limpa o array de perfis aqui, removendo valores vazios (como "")
            $perfisPostados = array_filter($_POST['perfis'] ?? []);
            // --- FIM DA CORREÇÃO ---

            $usuario->perfis = $perfisPostados; // Passa o array limpo para o Model
            $perfisDoUsuario = $perfisPostados; // Passa o array limpo para a View (repopular)
            
            try {
                $usuario->save();
                header('Location: ' . BASE_URL . '/index.php?url=/users');
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        // Passa os dados
        extract(['usuario' => $usuario, 'perfisDisponiveis' => $perfisDisponiveis, 'perfisDoUsuario' => $perfisDoUsuario, 'error' => $error]);
        require __DIR__ . '/../Views/users/editar.php';
    }

    public function delete($id)
    {
        SessionManager::require_auth(['Administrador']);
        
        if ($id == 1) {
            $errorMsg = "Erro: O usuário Administrador (ID 1) não pode ser excluído.";
            header('Location: ' . BASE_URL . '/index.php?url=/users&error=' . urlencode($errorMsg));
            exit;
        }
        
        if ($id == SessionManager::getUserId()) {
            $errorMsg = "Erro: Você não pode excluir sua própria conta.";
            header('Location: ' . BASE_URL . '/index.php?url=/users&error=' . urlencode($errorMsg));
            exit;
        }

        Usuario::delete($id);
        header('Location: ' . BASE_URL . '/index.php?url=/users');
        exit;
    }
}