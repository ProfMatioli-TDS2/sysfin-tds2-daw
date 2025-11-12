<?php
namespace App\Models;

use PDO;

class MovimentoCaixaModel
{
    public $db;

    public function __construct($db)
    {
        $this->db = $db;
    }


    public function getSaldoAtualEmCaixa() {
        $sql = "
            SELECT 
                (SUM(CASE WHEN tipo = 'E' THEN valor ELSE 0 END) - 
                 SUM(CASE WHEN tipo = 'S' THEN valor ELSE 0 END)) AS saldo
            FROM movimento_caixa
        ";
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $resultado['saldo'] ?? 0;
    }
    
    

    public function getProdutosComEstoqueBaixo()
    {
        $sql = 'SELECT COUNT(*) AS total 
                FROM produtos 
                WHERE estoque_atual <= estoque_minimo';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $resultado['total'] ?? 0;
    }

    
     public function getUltimosLancamentos()
     {
         $sql = "SELECT descricao, valor, tipo, data_movimento
                 FROM movimento_caixa
                 ORDER BY data_movimento DESC
                 LIMIT 5"; 
                 
         $stmt = $this->db->prepare($sql);
         $stmt->execute();
         return $stmt->fetchAll(PDO::FETCH_ASSOC);
     }


 
     public function getTotalVendidoHoje()
     {
         $sql = "
            SELECT SUM(valor) AS total_hoje
            FROM movimento_caixa
            WHERE tipo = 'E' 
         AND CAST(data_movimento AS DATE) = CURDATE()
         ";
         
         $stmt = $this->db->prepare($sql);
         $stmt->execute();
         $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
         
         return $resultado['total_hoje'] ?? 0;
     }

}
