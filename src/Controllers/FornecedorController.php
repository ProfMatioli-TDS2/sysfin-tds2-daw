<?php
namespace App\Controllers;

use App\Models\Fornecedor;

class FornecedorController
{
    private function verificarAdministrador()
    {
        session_start();
        $_SESSION['perfil'] = 'Administrador'; // Apenas para testes

        if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'Administrador') {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
    }

    public function index()
    {
        $this->verificarAdministrador();

        $nome = $_GET['busca'] ?? '';
        $fornecedores = $nome ? Fornecedor::searchByName($nome) : Fornecedor::getAll();

        require __DIR__ . '/../Views/fornecedores/index.php';
    }

    public function create()
    {
        $this->verificarAdministrador();
        $erros = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? '';
            $cnpj = $_POST['cnpj'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefone = $_POST['telefone'] ?? '';

            if (empty($nome)) {
                $erros['nome'] = "O campo Nome é obrigatório.";
            }

            if (empty($cnpj) || !$this->validarCNPJ($cnpj)) {
                $erros['cnpj'] = "CNPJ inválido ou não preenchido.";
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erros['email'] = "Email inválido ou não preenchido.";
            }

            if (empty($telefone) || !preg_match('/^\(\d{2}\) \d{5}-\d{4}$/', $telefone)) {
                $erros['telefone'] = "Telefone inválido ou não preenchido. Use o formato (XX) XXXXX-XXXX.";
            }

            if (empty($erros)) {
                $fornecedor = new Fornecedor();
                $fornecedor->nome = $nome;
                $fornecedor->cnpj = $cnpj;
                $fornecedor->email = $email;
                $fornecedor->telefone = $telefone;
                $fornecedor->save();

                header('Location: ' . BASE_URL . '/index.php?url=/fornecedores');
                exit;
            }
        }

        require __DIR__ . '/../Views/fornecedores/criar.php';
    }

    public function edit($id)
    {
        $this->verificarAdministrador();
        $fornecedor = Fornecedor::getById($id);
        $erros = [];

        if (!$fornecedor) {
            http_response_code(404);
            echo "Fornecedor não encontrado.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? '';
            $cnpj = $_POST['cnpj'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefone = $_POST['telefone'] ?? '';

            if (empty($nome)) {
                $erros['nome'] = "O campo Nome é obrigatório.";
            }

            if (empty($cnpj) || !$this->validarCNPJ($cnpj)) {
                $erros['cnpj'] = "CNPJ inválido ou não preenchido.";
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erros['email'] = "Email inválido ou não preenchido.";
            }

            if (empty($telefone) || !preg_match('/^\(\d{2}\) \d{5}-\d{4}$/', $telefone)) {
                $erros['telefone'] = "Telefone inválido ou não preenchido. Use o formato (XX) XXXXX-XXXX.";
            }

            if (empty($erros)) {
                $fornecedor->nome = $nome;
                $fornecedor->cnpj = $cnpj;
                $fornecedor->email = $email;
                $fornecedor->telefone = $telefone;
                $fornecedor->save();

                header('Location: ' . BASE_URL . '/index.php?url=/fornecedores');
                exit;
            }
        }

        require __DIR__ . '/../Views/fornecedores/editar.php';
    }

    public function delete($id)
    {
        $this->verificarAdministrador();

        Fornecedor::delete($id);

        header('Location: ' . BASE_URL . '/index.php?url=/fornecedores');
        exit;
    }

    public function report()
    {
        $this->verificarAdministrador();

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
