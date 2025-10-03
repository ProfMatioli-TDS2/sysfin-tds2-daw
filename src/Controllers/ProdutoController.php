<?php
namespace App\Controllers;

use App\Models\Produto;

class ProdutoController
{
    public function index()
    {
        $nomeBusca = $_GET['busca'] ?? '';
        $produtos = $nomeBusca ? Produto::searchByName($nomeBusca) : Produto::getAll();
        

        require __DIR__ . '/../Views/produtos/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            header('Location: /produtos');
            exit;
        }


        require __DIR__ . '/../Views/produtos/criar.php';
    }

    public function edit($id)
    {
        $produto = Produto::getById($id);

        if (!$produto) {
            echo "Produto não encontrado!";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            header('Location: /produtos');
            exit;
        }


        require __DIR__ . '/../Views/produtos/editar.php';
    }

    public function delete($id)
    {
        Produto::delete($id);
        header('Location: /produtos');
        exit;
    }

    public function report()
    {
        $produtos = Produto::getAll();
        

        require __DIR__ . '/../Views/produtos/relatorio.php';
    }
}