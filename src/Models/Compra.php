<?php
namespace App\Models;

use App\Core\Database; // Importa o Database Core
use \PDO;
use \PDOException;
use \Exception; // Adicionado para o 'throw new Exception'

class Compra
{
    /**
     * Esta é a Lógica de Negócio da Tarefa #7.
     * Ela recebe os dados da View (Tarefa #6) e executa a transação.
     */
    public static function registrarCompra($idFornecedor, $itens, $valorTotal) {

        if (empty($itens) || !is_array($itens)) {
            throw new Exception("Nenhum item informado para a compra.");
        }

        $pdo = Database::getConnection(); // Conexão Padrão

        try {
            // Requisito #2: Inicia a transação
            $pdo->beginTransaction();

            // Requisito #2a: Insere o cabeçalho da compra
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
                
                // Requisito #2b: Insere o item na tabela 'itens_compra'
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

                // Requisito #2c: Atualiza o estoque do produto
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

            // Requisito #2d: Insere a Saída no Caixa
            // (ID 2 = 'Compra de Mercadorias', 'S' = Saída)
            $stmtCaixa = $pdo->prepare("
                INSERT INTO movimento_caixa (data_movimento, descricao, id_plano_de_contas, tipo, valor, id_compra)
                VALUES (NOW(), 'Compra de Mercadorias', 2, 'S', :valor, :id_compra)
            ");
            $stmtCaixa->execute([
                ':valor' => $valorTotal,
                ':id_compra' => $idVenda
            ]);

            // Sucesso: Confirma a transação
            $pdo->commit();

            return $idVenda;

        } catch (Exception $e) {
            // Requisito #3: Desfaz tudo se der erro
            $pdo->rollBack();
            throw new Exception("Erro ao registrar compra: " . $e->getMessage());
        }
    }

    /**
     * Método do Relatório (da tarefa anterior)
     */
    public static function getComprasByPeriod($dataInicial, $dataFinal)
    {
        $conn = Database::getConnection(); 
        
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
            $stmt = $conn->prepare($sql);
            
            $dataInicialDB = date('Y-m-d 00:00:00', strtotime($dataInicial));
            $dataFinalDB = date('Y-m-d 23:59:59', strtotime($dataFinal));

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