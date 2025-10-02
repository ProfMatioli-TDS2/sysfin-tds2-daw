<?php
// App/Controllers/ClienteController.php 
namespace App\Controllers;
use App\Models\Cliente;
class ClienteController
{
    public function index()
    {
        $clientes = Cliente::getAll();
        require __DIR__ . '/../Views/cliente/index.php';
    }
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente = new Cliente();
            $cliente->nome = $_POST['nome'];
            $cliente->cpf_cnpj = $_POST['cpf_cnpj'];
            $cliente->email = $_POST['email'];
            $cliente->telefone = $_POST['telefone'];
            $cliente->save();
            header('Location: /clientes');
        } else {
            require __DIR__ . '/../Views/cliente/create.php';
        }
    }
    public function edit($id)
    {
        $cliente = Cliente::getById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente->nome = $_POST['nome'];
            $cliente->cpf_cnpj = $_POST['cpf_cnpj'];
            $cliente->email = $_POST['email'];
            $cliente->telefone = $_POST['telefone'];
            $cliente->save();
            header('Location: /clientes');
        } else {
            require __DIR__ . '/../Views/cliente/edit.php';
        }
    }
    public function delete($id)
    {
        Cliente::delete($id);
        header('Location: /clientes');
    }
}