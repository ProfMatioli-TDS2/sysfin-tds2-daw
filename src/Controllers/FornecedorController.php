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
            if (!$this->validarCNPJ($_POST['cnpj'])) {
                echo "CNPJ inválido.";
                return;
            }

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

        if (!$fornecedor) {
            http_response_code(404);
            echo "Fornecedor não encontrado.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->validarCNPJ($_POST['cnpj'])) {
                echo "CNPJ inválido.";
                return;
            }

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

    private function validarCNPJ($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) != 14) return false;
        if (preg_match('/^(.)\1*$/', $cnpj)) return false;

        for ($t = 12; $t < 14; $t++) {
            $d = 0;
            for ($m = $t - 7, $i = 0; $i < $t; $i++) {
                $d += $cnpj[$i] * $m;
                $m = ($m == 2) ? 9 : $m - 1;
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$t] != $d) return false;
        }

        return true;
    }
}
