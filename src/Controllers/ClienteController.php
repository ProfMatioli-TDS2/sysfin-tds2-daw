<?php
namespace App\Controllers;

// 1. IMPORTA O SESSION MANAGER
use App\Core\SessionManager; 
use App\Models\Cliente;
use Dompdf\Dompdf; 
use Dompdf\Options;

class ClienteController
{
    /**
     * Tela de Listagem (Read)
     */
    public function index()
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro']);
        
        $nome = $_GET['busca'] ?? '';
        $clientes = $nome ? Cliente::searchByName($nome) : Cliente::getAll();
        
        require __DIR__ . '/../Views/cliente/index.php';
    }

    /**
     * Tela de Criação (Create)
     */
    public function create()
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente = new Cliente();
            $cliente->nome = $_POST['nome'];
            $cliente->cpf_cnpj = $_POST['cpf_cnpj'];
            $cliente->email = $_POST['email'];
            $cliente->telefone = $_POST['telefone'];
            
            $cliente->save();
            
            header('Location: ' . BASE_URL . '/index.php?url=/clientes');
            exit;
        }

        require __DIR__ . '/../Views/cliente/create.php';
    }

    /**
     * Tela de Edição (Update)
     */
    public function edit($id)
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador']);
        
        $cliente = Cliente::getById($id);

        if (!$cliente) {
            echo "Cliente não encontrado!";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente->nome = $_POST['nome'];
            $cliente->cpf_cnpj = $_POST['cpf_cnpj'];
            $cliente->email = $_POST['email'];
            $cliente->telefone = $_POST['telefone'];
            
            $cliente->save();
            
            header('Location: ' . BASE_URL . '/index.php?url=/clientes');
            exit;
        }

        require __DIR__ . '/../Views/cliente/edit.php';
    }

    /**
     * Ação de Exclusão (Delete)
     */
    public function delete($id)
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador']);
        
        Cliente::delete($id);
        
        header('Location: ' . BASE_URL . '/index.php?url=/clientes');
        exit;
    }

    /**
     * Relatório (PDF)
     */
    public function report()
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro']);
        
        $clientes = Cliente::getAll();

        ob_start();
        require __DIR__ . '/../Views/cliente/report.php';
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait'); 
        $dompdf->render();
        
        $dompdf->stream("relatorio_clientes.pdf", ["Attachment" => false]);
    }
}