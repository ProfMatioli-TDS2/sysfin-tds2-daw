<?php

namespace App\Controllers;

use App\Models\Venda;
use App\Models\Cliente;
use App\Models\Produto;
use Exception;

class VendaController
{
    public function index()
    {
        $vendas = Venda::getAll();
        require __DIR__ . '/../Views/vendas/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }

        $clientes = Cliente::getAll();
        $produtos = Produto::getAll();
        require __DIR__ . '/../Views/venda/create.php';
    }

    private function store()
    {
        if (!isset($_POST['id_cliente']) || empty($_POST['id_cliente'])) {
            echo "Erro: Por favor, selecione um cliente.";
            exit;
        }
        if (!isset($_POST['itens']) || !is_array($_POST['itens']) || empty($_POST['itens'])) {
            echo "Erro: A venda deve conter pelo menos um item.";
            exit;
        }

        $valorTotal = 0;
        foreach ($_POST['itens'] as $item) {
            $valorTotal += $item['quantidade'] * $item['valor_unitario'];
        }

        $venda = new Venda();
        $venda->id_cliente = $_POST['id_cliente'];
        $venda->itens = $_POST['itens'];
        $venda->valor_total = $valorTotal;

        try {
            $venda->save();
            header('Location: /vendas');
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo "Falha ao processar a venda: " . $e->getMessage();
            exit;
        }
    }

    public function details($id)
    {
        $venda = Venda::getById($id);
        if (!$venda) {
            http_response_code(404);
            echo "Venda não encontrada.";
            exit;
        }
        require __DIR__ . '/../Views/vendas/details.php';
    }
}
