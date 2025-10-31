<?php
namespace App\Models;

use \PDO;
use \PDOException;

class Compra
{
    private $conn;

    public function __construct(PDO $db_connection)
    {
        $this->conn = $db_connection;
    }
    public function getComprasByPeriod($dataInicial, $dataFinal)
    {
        
        $sql = "
            SELECT 
                c.data_compra,      
                f.nome AS nome_fornecedor,
                c.valor_total
            FROM compras c
            JOIN fornecedores f ON c.id_fornecedor = f.id  
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

    /**
     * * @param array $dadosDaCompra 
     * @return bool 
     */
    public function salvar(array $dadosDaCompra)
    {
        $this->conn->beginTransaction();

        try {
            $sqlCompra = "INSERT INTO compras (id_fornecedor, data_compra, valor_total) 
                            VALUES (:fornecedor_id, :data_compra, :valor_total)";
            
            $stmtCompra = $this->conn->prepare($sqlCompra);
            
            $stmtCompra->execute([
                ':fornecedor_id' => $dadosDaCompra['fornecedor_id'],
                ':data_compra'   => $dadosDaCompra['data_compra'],
                ':valor_total'   => $dadosDaCompra['valor_total']
            ]);
            
            $idCompra = $this->conn->lastInsertId();
            $sqlItens = "INSERT INTO itens_compra (id_compra, id_produto, quantidade, preco_unitario) 
                            VALUES (:id_compra, :id_produto, :quantidade, :preco_unitario)";
            
            $stmtItens = $this->conn->prepare($sqlItens);
            
            foreach ($dadosDaCompra['itens'] as $item) {
                $stmtItens->execute([
                    ':id_compra'      => $idCompra,
                    ':id_produto'     => $item['produto_id'],
                    ':quantidade'     => $item['qtd'], 
                    ':preco_unitario' => $item['preco'] 
                ]);
            }

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Erro ao salvar compra: " . $e->getMessage());
            return false;
        }
    }
}