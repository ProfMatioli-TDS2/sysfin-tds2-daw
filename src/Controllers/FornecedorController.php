<?php
namespace App\Controllers;

// Importa o SessionManager
use App\Core\SessionManager; 
use App\Models\Fornecedor;
use Dompdf\Dompdf; 
use Dompdf\Options; 

class FornecedorController
{
    public function index()
    {
        // Proteção de acesso
        SessionManager::require_auth(['Administrador', 'Tesoureiro', 'Vendedor']);
        
        $nome = $_GET['busca'] ?? '';
        $fornecedores = $nome ? Fornecedor::searchByName($nome) : Fornecedor::getAll();

        require __DIR__ . '/../Views/fornecedores/index.php';
    }

    public function create()
    {
        // Proteção de acesso (Vendedor não pode cadastrar)
        SessionManager::require_auth(['Administrador']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fornecedor = new Fornecedor();
            $fornecedor->nome = $_POST['nome'];
            $fornecedor->cnpj = $_POST['cnpj'];
            $fornecedor->email = $_POST['email'];
            $fornecedor->telefone = $_POST['telefone'];
            $fornecedor->save();

            header('Location: ' . BASE_URL . '/index.php?url=/fornecedores');
            exit;
        }

        require __DIR__ . '/../Views/fornecedores/criar.php';
    }

    public function edit($id)
    {
        // Proteção de acesso (Vendedor não pode editar)
        SessionManager::require_auth(['Administrador']);
        
        $fornecedor = Fornecedor::getById($id);

        if (!$fornecedor) {
            http_response_code(404);
            echo "Fornecedor não encontrado.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fornecedor->nome = $_POST['nome'];
            $fornecedor->cnpj = $_POST['cnpj'];
            $fornecedor->email = $_POST['email'];
            $fornecedor->telefone = $_POST['telefone'];
            $fornecedor->save();

            header('Location: ' . BASE_URL . '/index.php?url=/fornecedores');
            exit;
        }

        require __DIR__ . '/../Views/fornecedores/editar.php';
    }

    public function delete($id)
    {
        // Proteção de acesso (Vendedor não pode excluir)
        SessionManager::require_auth(['Administrador']);
        
        Fornecedor::delete($id);

        header('Location: ' . BASE_URL . '/index.php?url=/fornecedores');
        exit;
    }

    public function report()
    {
        // Proteção de acesso
        SessionManager::require_auth(['Administrador', 'Tesoureiro', 'Vendedor']);
        
        $fornecedores = Fornecedor::getAll();

        ob_start();
        require __DIR__ . '/../Views/fornecedores/relatorio.php';
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $dompdf->stream("relatorio_fornecedores.pdf", ["Attachment" => false]);
    }

}