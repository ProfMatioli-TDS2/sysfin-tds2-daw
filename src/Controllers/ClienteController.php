<?php
namespace App\Controllers;

use App\Models\Cliente;
use Dompdf\Dompdf; // Importa o Dompdf (conforme seu composer.json)
use Dompdf\Options;

class ClienteController
{
    /**
     * Tela de Listagem (Read)
     * Exibe a lista de clientes com busca.
     */
    public function index()
    {
        $nome = $_GET['busca'] ?? '';
        $clientes = $nome ? Cliente::searchByName($nome) : Cliente::getAll();
        
        // Carrega a view da lista
        require __DIR__ . '/../Views/cliente/index.php';
    }

    /**
     * Tela de Criação (Create)
     * Exibe o formulário (GET) ou processa o novo cliente (POST).
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente = new Cliente();
            $cliente->nome = $_POST['nome'];
            $cliente->cpf_cnpj = $_POST['cpf_cnpj'];
            $cliente->email = $_POST['email'];
            $cliente->telefone = $_POST['telefone'];
            
            $cliente->save();
            
            // CORRIGIDO: Redirecionamento usando a URL base (ponto em vez de underscore)
            header('Location: ' . BASE_URL . '/index.php?url=/clientes');
            exit;
        }

        // Exibe o formulário de criação
        require __DIR__ . '/../Views/cliente/create.php';
    }

    /**
     * Tela de Edição (Update)
     * Exibe o formulário pré-preenchido (GET) ou atualiza o cliente (POST).
     */
    public function edit($id)
    {
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
            
            // CORRIGIDO: Redirecionamento usando a URL base (ponto em vez de underscore)
            header('Location: ' . BASE_URL . '/index.php?url=/clientes');
            exit;
        }

        require __DIR__ . '/../Views/cliente/edit.php';
    }

    /**
     * Ação de Exclusão (Delete)
     * Processa a exclusão e redireciona.
     */
    public function delete($id)
    {
        Cliente::delete($id);
        
        // CORRIGIDO: Redirecionamento usando a URL base (ponto em vez de underscore)
        header('Location: ' . BASE_URL . '/index.php?url=/clientes');
        exit;
    }

    /**
     * Relatório (PDF)
     * Gera um PDF da lista de clientes.
     */
    public function report()
    {
        // Implementação correta do PDF
        $clientes = Cliente::getAll();

        // Carrega o HTML da view em uma variável
        ob_start();
        require __DIR__ . '/../Views/cliente/report.php';
        $html = ob_get_clean();

        // Configura o Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait'); // Configura a página
        $dompdf->render();
        
        // Envia o PDF para o navegador
        $dompdf->stream("relatorio_clientes.pdf", ["Attachment" => false]);
    }
}