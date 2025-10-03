<?php
namespace App\Controllers;

use App\Models\Fornecedor;

class FornecedorController
{
    public function index()
    {
        $nome = $_GET['busca'] ?? '';
        $fornecedores = $nome ? Fornecedor::searchByName($nome) : Fornecedor::getAll();
        require __DIR__ . '/../Views/fornecedores/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fornecedor = new Fornecedor();
            $fornecedor->nome = $_POST['nome'];
            $fornecedor->cnpj = $_POST['cnpj'];
            $fornecedor->email = $_POST['email'];
            $fornecedor->telefone = $_POST['telefone'];
            $fornecedor->save();
            header('Location: /fornecedores');
            exit;
        }

        require __DIR__ . '/../Views/fornecedores/criar.php';
    }

    public function edit($id)
    {
        $fornecedor = Fornecedor::getById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fornecedor->nome = $_POST['nome'];
            $fornecedor->cnpj = $_POST['cnpj'];
            $fornecedor->email = $_POST['email'];
            $fornecedor->telefone = $_POST['telefone'];
            $fornecedor->save();
            header('Location: /fornecedores');
            exit;
        }

        require __DIR__ . '/../Views/fornecedores/editar.php';
    }

    public function delete($id)
    {
        Fornecedor::delete($id);
        header('Location: /fornecedores');
        exit;
    }

    public function report()
    {
        $fornecedores = Fornecedor::getAll();
        require __DIR__ . '/../Views/fornecedores/relatorio.php';
    }
}
