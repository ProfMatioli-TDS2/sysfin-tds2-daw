<?php
namespace App\Controllers;

use App\Models\Usuario;
use App\Models\Perfil; // Precisa do Model de Perfil
use Exception;

class UserController
{
    /**
     * Lista de Usuários
     */
    public function index()
    {
        $usuarios = Usuario::getAll();
        $error = $_GET['error'] ?? null;
        
        require __DIR__ . '/../Views/users/index.php';
    }

    /**
     * Formulário de Criação
     */
    public function create()
    {
        $perfisDisponiveis = Perfil::getAll(); // Para os checkboxes
        $error = null;
        $usuario = new Usuario(); // Para manter os dados no form se der erro

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->nome = $_POST['nome'] ?? '';
            $usuario->login = $_POST['login'] ?? '';
            $usuario->senha = $_POST['senha'] ?? '';
            $usuario->ativo = isset($_POST['ativo']) ? 1 : 0;
            $usuario->perfis = $_POST['perfis'] ?? []; // Array de IDs
            
            try {
                // O Model (save) fará a validação de "pelo menos um perfil"
                $usuario->save();
                header('Location: ' . BASE_URL . '/index.php?url=/users');
                exit;
            } catch (Exception $e) {
                // Captura erros (ex: "Você deve selecionar...", "login duplicado", etc.)
                $error = $e->getMessage();
            }
        }
        
        // Passa os dados para a view
        extract(['usuario' => $usuario, 'perfisDisponiveis' => $perfisDisponiveis, 'error' => $error]);
        require __DIR__ . '/../Views/users/criar.php';
    }

    /**
     * Formulário de Edição
     */
    public function edit($id)
    {
        $usuario = Usuario::getById($id);
        if (!$usuario) {
            header('Location: ' . BASE_URL . '/index.php?url=/users');
            exit;
        }

        $perfisDisponiveis = Perfil::getAll(); // Para os checkboxes
        $perfisDoUsuario = $usuario->getProfileIds(); // Para marcar os checkboxes
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->nome = $_POST['nome'];
            $usuario->login = $_POST['login'];
            $usuario->senha = $_POST['senha']; // Opcional
            $usuario->ativo = isset($_POST['ativo']) ? 1 : 0;
            $usuario->perfis = $_POST['perfis'] ?? [];
            
            try {
                // O Model (save) fará a validação de "pelo menos um perfil"
                $usuario->save();
                header('Location: ' . BASE_URL . '/index.php?url=/users');
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        // Passa os dados para a view
        extract(['usuario' => $usuario, 'perfisDisponiveis' => $perfisDisponiveis, 'perfisDoUsuario' => $perfisDoUsuario, 'error' => $error]);
        require __DIR__ . '/../Views/users/editar.php';
    }

    /**
     * Lógica de Exclusão
     */
    public function delete($id)
    {
        if ($id == 1) {
            $errorMsg = "Erro: O usuário Administrador (ID 1) não pode ser excluído.";
            header('Location: ' . BASE_URL . '/index.php?url=/users&error=' . urlencode($errorMsg));
            exit;
        }

        Usuario::delete($id);
        header('Location: ' . BASE_URL . '/index.php?url=/users');
        exit;
    }
}