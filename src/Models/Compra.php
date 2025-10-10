<?php
namespace App\Models;

use \PDO;
use \PDOException;

class Compra
{
    private $conn;

    public function __construct()
        {
            $host = '143.106.241.4'; 
            $dbname = 'matioli';    
            $user = 'bancomatioli'; 
            $pass = 'senhabanco';   
            
            try {
                $this->conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                
                die("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
            }
        }

    public function getComprasByPeriod($dataInicial, $dataFinal)
    {
        
        $sql = "
            SELECT 
                c.data_compra,      -- Nome da coluna confirmado: data_compra
                f.nome AS nome_fornecedor,
                c.valor_total
            FROM compras c
            JOIN fornecedores f ON c.id_fornecedor = f.id  -- Ligação corrigida: id_fornecedor
            WHERE c.data_compra BETWEEN :dataInicial AND :dataFinal
            ORDER BY c.data_compra ASC
        ";

        try {
            $stmt = $this->conn->prepare($sql);
            $dataInicialDB = date('Y-m-d', strtotime($dataInicial));
            $dataFinalDB = date('Y-m-d', strtotime($dataFinal));

            $stmt->bindParam(':dataInicial', $dataInicialDB);
            $stmt->bindParam(':dataFinal', $dataFinalDB);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            
            error_log("Erro na consulta SQL: " . $e->getMessage());
            
            return [];
        }
    }
}