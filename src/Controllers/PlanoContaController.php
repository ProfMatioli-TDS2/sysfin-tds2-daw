<?php
namespace App\Controllers;

// 1. IMPORTA O SESSION MANAGER
use App\Core\SessionManager;
use App\Models\PlanoConta;
use Dompdf\Dompdf;
use Dompdf\Options;

class PlanoContaController
{
    /**
     * Tela de Listagem (Read)
     */
    public function index()
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro']);
        
        $planosConta = PlanoConta::getAll();
        
        // (Esta variável $error é usada se a exclusão falhar)
        $error = $_GET['error'] ?? null; 
        
        require __DIR__ . '/../Views/plano-contas/index.php';
    }

    /**
     * Tela de Criação (Create)
     */
    public function create()
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $planoConta = new PlanoConta();
            $planoConta->descricao = $_POST['descricao'];
            $planoConta->tipo = $_POST['tipo']; // 'R' ou 'D' vindo do formulário
            
            $planoConta->save();
            
            header('Location: ' . BASE_URL . '/index.php?url=/plano-contas');
            exit;
        }

        require __DIR__ . '/../Views/plano-contas/criar.php';
    }

    /**
     * Tela de Edição (Update)
     */
    public function edit($id)
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro']);
        
        $planoConta = PlanoConta::getById($id);

        if (!$planoConta) {
            echo "Plano de Conta não encontrado!";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $planoConta->descricao = $_POST['descricao'];
            $planoConta->tipo = $_POST['tipo'];
            
            // Contas padrão (1-5) só podem ter a descrição alterada
            if (PlanoConta::isProtegido($id)) {
                $original = PlanoConta::getById($id);
                $planoConta->tipo = $original->tipo; // Força o tipo original
            }
            
            $planoConta->save();
            
            header('Location: ' . BASE_URL . '/index.php?url=/plano-contas');
            exit;
        }

        require __DIR__ . '/../Views/plano-contas/editar.php';
    }

    /**
     * Ação de Exclusão (Delete)
     */
    public function delete($id)
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro']);
        
        // VERIFICAÇÃO 1: É uma conta padrão (ID 1-5)?
        if (PlanoConta::isProtegido($id)) {
            $errorMsg = "Erro: Esta é uma conta padrão e não pode ser excluída.";
            header('Location: ' . BASE_URL . '/index.php?url=/plano-contas&error=' . urlencode($errorMsg));
            exit;
        }

        // VERIFICAÇÃO 2: Já foi usada em algum lançamento?
        if (PlanoConta::hasMovimentacoes($id)) {
            $errorMsg = "Erro: Esta conta possui lançamentos financeiros e não pode ser excluída.";
            header('Location: ' . BASE_URL . '/index.php?url=/plano-contas&error=' . urlencode($errorMsg));
            exit;
        }

        // Pode excluir
        PlanoConta::delete($id);
        header('Location: ' . BASE_URL . '/index.php?url=/plano-contas');
        exit;
    }

    /**
     * Relatório (PDF)
     */
    public function report()
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro']);
        
        $planosConta = PlanoConta::getAll();

        ob_start();
        require __DIR__ . '/../Views/plano-contas/relatorio.php';
        $html = ob_get_clean();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $dompdf->stream("relatorio_plano_de_contas.pdf", ["Attachment" => false]);
    }
}