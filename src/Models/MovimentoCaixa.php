<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class MovimentoCaixa
{
    // ... (propriedades id, data_movimento, etc.) ...
    
    /**
     * Salva um lançamento manual (da Tarefa #10)
     */
    public static function saveManual(
        string $data, 
        string $descricao, 
        int $idPlanoConta, 
        string $tipo, 
        float $valor
    ): bool
    {
        $pdo = Database::getConnection();
        try {
            $sql = "INSERT INTO movimento_caixa 
                        (data_movimento, descricao, id_plano_de_contas, tipo, valor)
                    VALUES 
                        (:data, :descricao, :id_plano, :tipo, :valor)";
            
            $stmt = $pdo->prepare($sql);
            
            return $stmt->execute([
                ':data' => $data,
                ':descricao' => $descricao,
                ':id_plano' => $idPlanoConta,
                ':tipo' => $tipo,
                ':valor' => $valor
            ]);

        } catch (Exception $e) {
            error_log("Erro ao salvar lançamento manual: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Busca os últimos N lançamentos (para o Dashboard)
     */
    public static function getUltimosLancamentos(int $limite = 5)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query(
            "SELECT data_movimento, descricao, tipo, valor 
             FROM movimento_caixa 
             ORDER BY data_movimento DESC, id DESC
             LIMIT " . $limite
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // ==========================================================
    // NOVOS MÉTODOS PARA A TAREFA #11 (Relatório de Caixa)
    // ==========================================================

    /**
     * REQUISITO #2a: Saldo Anterior
     * Calcula o saldo total de todos os tempos ANTES da data inicial.
     */
    public static function getSaldoAnterior(string $dataInicial): float
    {
        $pdo = Database::getConnection();
        
        $sql = "SELECT 
                    (SUM(CASE WHEN tipo = 'E' THEN valor ELSE 0 END) - 
                     SUM(CASE WHEN tipo = 'S' THEN valor ELSE 0 END)) AS saldo_anterior
                FROM movimento_caixa
                WHERE data_movimento < :dataInicial";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':dataInicial' => date('Y-m-d 00:00:00', strtotime($dataInicial))]);
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (float) ($resultado['saldo_anterior'] ?? 0.0);
    }

    /**
     * REQUISITO #2b: Lista de Movimentações
     * Busca todos os lançamentos DENTRO do período.
     */
    public static function getMovimentacoesPorPeriodo(string $dataInicial, string $dataFinal): array
    {
        $pdo = Database::getConnection();
        
        $sql = "SELECT data_movimento, descricao, tipo, valor
                FROM movimento_caixa
                WHERE data_movimento BETWEEN :dataInicial AND :dataFinal
                ORDER BY data_movimento ASC, id ASC";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':dataInicial' => date('Y-m-d 00:00:00', strtotime($dataInicial)),
            ':dataFinal'   => date('Y-m-d 23:59:59', strtotime($dataFinal))
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}