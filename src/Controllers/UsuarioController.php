<?php
namespace App\Controllers;
use App\Core\Database;
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

            $db = Database::getConnection(); 
            
            $usuarioModel = new Usuario($db);

            $sucesso = $usuarioModel->create($nome, $email, $senha, $perfis_ids);

            if ($sucesso) {
                header('Location: /login?success=registro_ok');
                exit;
            } else {
                header('Location: /usuarios/criar?error=email_duplicado');
                exit;
            }
        }

        $data = [
            'perfis' => Perfil::getAll() 
        ];
        $this->renderView('usuario/create', $data);
    }
}
