<?php
namespace App\Controllers;

use App\Models\Perfil;

class ProfileController
{
    /**
     * Lista de Perfis
     */
    public function index()
    {
        $perfis = Perfil::getAll();
        $error = $_GET['error'] ?? null;
        
        require __DIR__ . '/../Views/profiles/index.php';
    }

    /**
     * Formulário de Criação
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $perfil = new Perfil();
            $perfil->nome = $_POST['nome'];
            $perfil->save();
            header('Location: ' . BASE_URL . '/index.php?url=/profiles');
            exit;
        }
        
        require __DIR__ . '/../Views/profiles/criar.php';
    }

    /**
     * Formulário de Edição
     */
    public function edit($id)
    {
        $perfil = Perfil::getById($id);
        if (!$perfil) {
            header('Location: ' . BASE_URL . '/index.php?url=/profiles');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $perfil->nome = $_POST['nome'];
            $perfil->save();
            header('Location: ' . BASE_URL . '/index.php?url=/profiles');
            exit;
        }
        
        require __DIR__ . '/../Views/profiles/editar.php';
    }

    /**
     * Lógica de Exclusão (seguindo seu routes.php com GET)
     */
    public function delete($id)
    {
        // Regra: Não excluir perfis padrão (1, 2, 3 do PDF)
        if ($id <= 3) {
            $errorMsg = "Erro: Perfis padrão (Administrador, Tesoureiro, Vendedor) não podem ser excluídos.";
            header('Location: ' . BASE_URL . '/index.php?url=/profiles&error=' . urlencode($errorMsg));
            exit;
        }
        
        // Regra: Não excluir perfil se estiver em uso
        if (Perfil::isProfileInUse($id)) {
            $errorMsg = "Erro: Este perfil está associado a um ou mais usuários e não pode ser excluído.";
            header('Location: ' . BASE_URL . '/index.php?url=/profiles&error=' . urlencode($errorMsg));
            exit;
        }

        Perfil::delete($id);
        header('Location: ' . BASE_URL . '/index.php?url=/profiles');
        exit;
    }
}