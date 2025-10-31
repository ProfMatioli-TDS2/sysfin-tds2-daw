<?php
namespace App\Controllers;

use App\Models\Usuario;
use App\Models\Perfil;

class UsuarioController
{
    private function renderView(string $viewPath, array $data = [])
    {
        extract($data); 
        $fullPath = __DIR__ . "/../Views/{$viewPath}.php";
        if (file_exists($fullPath)) {
            include $fullPath;
        } else {
            echo "Erro: View não encontrada em " . $fullPath;
        }
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? null;
            $email = $_POST['email'] ?? null;
            $senha = $_POST['senha'] ?? null;
            $senha_conf = $_POST['senha_confirmacao'] ?? null;
            $perfis_ids = $_POST['perfis'] ?? []; 

            if (empty($nome) || empty($email) || empty($senha) || $senha !== $senha_conf || empty($perfis_ids)) {
                header('Location: /usuarios/criar?error=validacao');
                exit;
            }

            $usuarioModel = new Usuario();
            
            // Tenta criar o usuário
            $sucesso = $usuarioModel->create($nome, $email, $senha, $perfis_ids);

            if ($sucesso) {
                // Redireciona para o login com mensagem de sucesso
                header('Location: /login?success=registro_ok');
                exit;
            } else {
                // Pode ser email duplicado ou outro erro de DB
                header('Location: /usuarios/criar?error=email_duplicado');
                exit;
            }
        }

        // Se for GET, busca os perfis e mostra o formulário
        $data = [
            'perfis' => Perfil::getAll() // Busca perfis para os checkboxes
        ];
        $this->renderView('usuario/create', $data);
    }
}
