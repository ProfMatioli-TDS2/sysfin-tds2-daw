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

        public static function registrarCompra($idFornecedor, $itens, $valorTotal) {

            if (empty($itens) || !is_array($itens)) {
                throw new Exception("Nenhum item informado para a compra.");
            }

            $pdo = Database::getConnection();

            try {
                $pdo->beginTransaction();

                $stmtVenda = $pdo->prepare("
                    INSERT INTO compras (id_fornecedor, data_compra, valor_total)
                    VALUES (:id_fornecedor, NOW(), :valor_total)
                ");
                $stmtVenda->execute([
                    ':id_fornecedor' => $idFornecedor,
                    ':valor_total' => $valorTotal
                ]);
                $idVenda = $pdo->lastInsertId();

                foreach ($itens as $item) {
                    $stmtItem = $pdo->prepare("
                        INSERT INTO itens_compra (id_compra, id_produto, quantidade, valor_unitario)
                        VALUES (:id_compra, :id_produto, :quantidade, :valor_unitario)
                    ");
                    $stmtItem->execute([
                        ':id_compra' => $idVenda,
                        ':id_produto' => $item['id_produto'],
                        ':quantidade' => $item['quantidade'],
                        ':valor_unitario' => $item['valor_unitario']
                    ]);

                    $stmtEstoque = $pdo->prepare("
                        UPDATE produtos
                        SET estoque_atual = estoque_atual + :qtd
                        WHERE id = :id_produto
                    ");
                    $stmtEstoque->execute([
                        ':qtd' => $item['quantidade'],
                        ':id_produto' => $item['id_produto']
                    ]);
                }

                $stmtCaixa = $pdo->prepare("
                    INSERT INTO movimento_caixa (data_movimento, descricao, id_plano_de_contas, tipo, valor, id_compra)
                    VALUES (NOW(), 'Compra de Mercadorias', 2, 'S', :valor, :id_compra)
                ");
                $stmtCaixa->execute([
                    ':valor' => $valorTotal,
                    ':id_compra' => $idVenda
                ]);

                $pdo->commit();

                return $idVenda;

            } catch (Exception $e) {
                $pdo->rollBack();
                throw new Exception("Erro ao registrar compra: " . $e->getMessage());
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