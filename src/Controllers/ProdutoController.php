<?php
namespace App\Controllers;

use App\Models\Produto;

class ProdutoController
{
    public function index()
    {
        $nomeBusca = $_GET['busca'] ?? '';
        $produtos = $nomeBusca ? Produto::searchByName($nomeBusca) : Produto::getAll();
        require __DIR__ . '/../Views/produto/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $produto = new Produto();
            $produto->nome = $_POST['nome'];
            $produto->descricao = $_POST['descricao'];
            $produto->preco_venda = $_POST['preco_venda'];
            $produto->estoque = $_POST['estoque'];
            $produto->estoque_minimo = $_POST['estoque_minimo'];
            $produto->save();
            header('Location: ' . BASE_URL . '/index.php?url=/produtos');
            exit;
        }
        require __DIR__ . '/../Views/produto/criar.php';
    }

    public function edit($id)
    {
        $produto = Produto::getById($id);

        if (!$produto) {
            echo "Produto não encontrado!";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $produto->nome = $_POST['nome'];
            $produto->descricao = $_POST['descricao'];
            $produto->preco_venda = $_POST['preco_venda'];
            $produto->estoque_minimo = $_POST['estoque_minimo'];
            $produto->save();
            header('Location: ' . BASE_URL . '/index.php?url=/produtos');
            exit;
        }
        require __DIR__ . '/../Views/produto/editar.php';
    }

    public function delete($id)
    {
        Produto::delete($id);
        header('Location: ' . BASE_URL . '/index.php?url=/produtos');
        exit;
    }

    public function report()
    {
        $produtos = Produto::getAll();
        require __DIR__ . '/../Views/produto/relatorio.php';
    }


    
}