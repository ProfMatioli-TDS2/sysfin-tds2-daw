<?php
namespace App\Controllers;

use App\Models\Produto;
use Dompdf\Dompdf; // Importa o Dompdf
use Dompdf\Options; // Importa o Options

class ProdutoController
{
    public function index()
    {
        $nomeBusca = $_GET['busca'] ?? '';
        $produtos = $nomeBusca ? Produto::searchByName($nomeBusca) : Produto::getAll();
        
        // Corrigido para o caminho correto da sua View (baseado no CRUD de Clientes)
        require __DIR__ . '/../Views/produto/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $produto = new Produto();
            $produto->nome = $_POST['nome'];
            $produto->descricao = $_POST['descricao'];
            $produto->preco_venda = $_POST['preco_venda'];
            
            // REGRA DE NEGÓCIO: O estoque (estoque_atual) só é definido na criação.
            $produto->estoque = $_POST['estoque_atual']; // Assumindo 'estoque_atual' no form
            
            $produto->estoque_minimo = $_POST['estoque_minimo'];
            $produto->save();
            
            header('Location: ' . BASE_URL . '/index.php?url=/produtos');
            exit;
        }
        
        // Corrigido para o caminho correto da sua View
        require __DIR__ . '/../Views/produto/criar.php';
    }

    public function edit($id)
    {
        $produto = Produto::getById($id);

        if (!$produto) {
            echo "Produto não encontrado!";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $produto->nome = $_POST['nome'];
            $produto->descricao = $_POST['descricao'];
            $produto->preco_venda = $_POST['preco_venda'];
            
            // REGRA DE NEGÓCIO: O estoque (estoque_atual) NÃO é alterado na edição.
            // Apenas o estoque_minimo é permitido.
            $produto->estoque_minimo = $_POST['estoque_minimo'];
            
            $produto->save();
            
            header('Location: ' . BASE_URL . '/index.php?url=/produtos');
            exit;
        }
        
        // Corrigido para o caminho correto da sua View
        require __DIR__ . '/../Views/produto/editar.php';
    }

    public function delete($id)
    {
        // AINDA FALTA: A regra de verificar se o produto foi usado.
        // Por enquanto, apenas exclui:
        Produto::delete($id);
        header('Location: ' . BASE_URL . '/index.php?url=/produtos');
        exit;
    }

    /**
     * CORRIGIDO: Agora o Controller gera o PDF
     */
    public function report()
    {
        $produtos = Produto::getAll();

        // Carrega o HTML da view (relatorio.php) em uma variável
        ob_start();
        // O template do relatório (próximo arquivo)
        require __DIR__ . '/../Views/produto/relatorio.php';
        $html = ob_get_clean();

        // Configura o Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Envia o PDF para o navegador
        $dompdf->stream("relatorio_produtos.pdf", ["Attachment" => false]);
    }
}