<?php
namespace App\Controllers;

use App\Models\Fornecedor;

/**
 * Controller responsável pela gestão de fornecedores.
 * Implementa as operações de CRUD e geração de relatório.
 * 
 * IMPORTANTE:
 * A verificação de acesso por perfil 'Administrador' depende da implementação da Tarefa 16.
 * A variável de sessão $_SESSION['perfil'] deve ser definida no login.
 */




class FornecedorController
{



    /**
     * Verifica se o usuário logado possui perfil 'Administrador'.
     * Caso contrário, bloqueia o acesso à funcionalidade.
     */
    private function verificarAdministrador()
    {
        session_start();

    /* Declarando variável de sessão, apenas para testes. EXCLUIR DEPOIS PARA NÃO prejudicar o sistema! */
    $_SESSION['perfil'] = 'Administrador';

        if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] !== 'Administrador') {
            http_response_code(403);
            echo "Acesso negado.";
            exit;
        }
    }

    /**
     * Exibe a lista de fornecedores.
     * Permite busca por nome via parâmetro GET.
     */
    public function index()
    {
        $this->verificarAdministrador();

        $nome = $_GET['busca'] ?? '';
        $fornecedores = $nome ? Fornecedor::searchByName($nome) : Fornecedor::getAll();

        // Carrega a view de listagem
        require __DIR__ . '/../Views/fornecedores/index.php';
    }

    /**
     * Exibe o formulário de criação e processa o cadastro de novo fornecedor.
     * Valida o CNPJ antes de salvar.
     */
    public function create()
    {
    $this->verificarAdministrador();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validações obrigatórias
        if (empty($_POST['nome'])) {
            echo "O campo Nome é obrigatório.";
            return;
        }

        if (empty($_POST['cnpj']) || !$this->validarCNPJ($_POST['cnpj'])) {
            echo "CNPJ inválido ou não preenchido.";
            return;
        }

        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo "Email inválido ou não preenchido.";
            return;
        }

        if (empty($_POST['telefone']) || !preg_match('/^\(\d{2}\) \d{5}-\d{4}$/', $_POST['telefone'])) {
            echo "Telefone inválido ou não preenchido. Use o formato (XX) XXXXX-XXXX.";
            return;
        }

        // Criação do objeto e persistência no banco
        $fornecedor = new Fornecedor();
        $fornecedor->nome = $_POST['nome'];
        $fornecedor->cnpj = $_POST['cnpj'];
        $fornecedor->email = $_POST['email'];
        $fornecedor->telefone = $_POST['telefone'];
        $fornecedor->save();

        // Redireciona para a listagem após salvar
        header('Location: /fornecedores');
        exit;
    }

    // Carrega a view de criação
    require __DIR__ . '/../Views/fornecedores/criar.php';
    }

    /**
     * Exibe o formulário de edição e processa a atualização de fornecedor existente.
     * Valida o CNPJ antes de salvar.
     */
    public function edit($id)
    {
        $this->verificarAdministrador();

    // Busca o fornecedor pelo ID
    $fornecedor = Fornecedor::getById($id);

    if (!$fornecedor) {
        http_response_code(404);
        echo "Fornecedor não encontrado.";
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validações obrigatórias
        if (empty($_POST['nome'])) {
            echo "O campo Nome é obrigatório.";
            return;
        }

        if (empty($_POST['cnpj']) || !$this->validarCNPJ($_POST['cnpj'])) {
            echo "CNPJ inválido ou não preenchido.";
            return;
        }

        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo "Email inválido ou não preenchido.";
            return;
        }

        if (empty($_POST['telefone']) || !preg_match('/^\(\d{2}\) \d{5}-\d{4}$/', $_POST['telefone'])) {
            echo "Telefone inválido ou não preenchido. Use o formato (XX) XXXXX-XXXX.";
            return;
        }

        // Atualiza os dados e salva
        $fornecedor->nome = $_POST['nome'];
        $fornecedor->cnpj = $_POST['cnpj'];
        $fornecedor->email = $_POST['email'];
        $fornecedor->telefone = $_POST['telefone'];
        $fornecedor->save();

        // Redireciona para a listagem após editar
        header('Location: /fornecedores');
        exit;
    }

    // Carrega a view de edição
    require __DIR__ . '/../Views/fornecedores/editar.php';
    }

    /**
     * Exclui um fornecedor pelo ID.
     */
    public function delete($id)
    {
        $this->verificarAdministrador();

        // Executa a exclusão
        Fornecedor::delete($id);

        // Redireciona para a listagem
        header('Location: /fornecedores');
        exit;
    }

    /**
     * Gera o relatório em PDF com todos os fornecedores.
     */
    public function report()
    {
        $this->verificarAdministrador();

        $fornecedores = Fornecedor::getAll();

        // Carrega a view que gera o PDF
        require __DIR__ . '/../Views/fornecedores/relatorio.php';
    }

    /**
     * Valida o formato e os dígitos verificadores de um CNPJ.
     * Retorna true se válido, false se inválido.
     */
    private function validarCNPJ($cnpj)
    {
        // Remove caracteres não numéricos
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        // Verifica tamanho e repetições
        if (strlen($cnpj) != 14) return false;
        if (preg_match('/^(.)\1*$/', $cnpj)) return false;

        // Validação dos dígitos verificadores
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
