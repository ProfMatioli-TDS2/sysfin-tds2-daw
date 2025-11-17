<?php
namespace App\Controllers;

// 1. IMPORTA O SESSION MANAGER
use App\Core\SessionManager;
use App\Models\PlanoConta;
use App\Models\MovimentoCaixa;
use Exception;

class LancamentoCaixaController
{
    /**
     * Exibe o formulário (GET) ou processa o lançamento (POST).
     */
    public function create()
    {
        // 2. PROTEÇÃO DO MÉTODO
        SessionManager::require_auth(['Administrador', 'Tesoureiro']);
        
        $data = [
            'planosConta' => PlanoConta::getAll(),
            'error' => null,
            'success' => null
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // 1. Coletar dados do formulário
                $dataLancamento = $_POST['data_movimento'];
                $descricao = $_POST['descricao'];
                $valor = $_POST['valor'];
                $tipo = $_POST['tipo']; // 'E' (Entrada) ou 'S' (Saída)
                $idPlanoConta = $_POST['id_plano_de_contas'];
                
                // 2. Limpar o valor (ex: "1.250,50" -> 1250.50)
                $valorLimpo = str_replace('.', '', $valor);
                $valorLimpo = (float) str_replace(',', '.', $valorLimpo);

                if (empty($dataLancamento) || empty($descricao) || $valorLimpo <= 0 || empty($tipo) || empty($idPlanoConta)) {
                    throw new Exception("Por favor, preencha todos os campos corretamente.");
                }

                // 3. Chamar o Model para salvar
                $sucesso = MovimentoCaixa::saveManual(
                    $dataLancamento,
                    $descricao,
                    $idPlanoConta,
                    $tipo,
                    $valorLimpo
                );

                if ($sucesso) {
                    // Limpa os dados do POST para o formulário aparecer vazio
                    $_POST = [];
                    $data['success'] = "Lançamento manual salvo com sucesso!";
                } else {
                    throw new Exception("Não foi possível salvar o lançamento.");
                }

            } catch (Exception $e) {
                $data['error'] = $e->getMessage();
            }
        }
        
        // 4. Extrai os dados ($planosConta, $error, $success) para a view
        extract($data);
        
        // 5. Carrega a view do formulário
        require __DIR__ . '/../Views/lancamento-caixa/create.php';
    }
}