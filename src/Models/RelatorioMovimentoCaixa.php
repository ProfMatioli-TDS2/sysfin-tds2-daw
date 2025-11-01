<?php
namespace App\Models;

use App\Core\Database;

class RelatorioMovimentoCaixa
{
    public static function getSaldoAnterior($dataInicial)
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT 
                SUM(CASE WHEN tipo = 'E' THEN valor ELSE -valor END) AS saldo_anterior
            FROM movimento_caixa
            WHERE data_movimento < :data_inicial
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':data_inicial', $dataInicial);
        $stmt->execute();

        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado['saldo_anterior'] ?? 0;
    }

    public static function getMovimentacoes($dataInicial, $dataFinal)
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT 
                data_movimento,
                descricao,
                tipo,
                valor
            FROM movimento_caixa
            WHERE data_movimento BETWEEN :data_inicial AND :data_final
            ORDER BY data_movimento ASC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':data_inicial', $dataInicial);
        $stmt->bindValue(':data_final', $dataFinal);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getTotais($dataInicial, $dataFinal)
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT 
                SUM(CASE WHEN tipo = 'E' THEN valor ELSE 0 END) AS total_entradas,
                SUM(CASE WHEN tipo = 'S' THEN valor ELSE 0 END) AS total_saidas
            FROM movimento_caixa
            WHERE data_movimento BETWEEN :data_inicial AND :data_final
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':data_inicial', $dataInicial);
        $stmt->bindValue(':data_final', $dataFinal);
        $stmt->execute();

        $totais = $stmt->fetch(\PDO::FETCH_ASSOC);
        return [
            'entradas' => $totais['total_entradas'] ?? 0,
            'saidas' => $totais['total_saidas'] ?? 0,
        ];
    }
}
