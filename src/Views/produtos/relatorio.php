<?php
// Usa a biblioteca Dompdf que já está no projeto via Composer
use Dompdf\Dompdf;
use Dompdf\Options;

// Prepara o HTML que será convertido em PDF
$html = '
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Produtos</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Relatório de Produtos Cadastrados</h1>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço de Venda</th>
                <th>Estoque</th>
            </tr>
        </thead>
        <tbody>';

// Adiciona uma linha na tabela para cada produto
foreach ($produtos as $produto) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($produto->nome) . '</td>';
    $html .= '<td>' . htmlspecialchars($produto->descricao) . '</td>';
    $html .= '<td>R$ ' . number_format($produto->preco_venda, 2, ',', '.') . '</td>';
    $html .= '<td>' . $produto->estoque . '</td>';
    $html .= '</tr>';
}

$html .= '
        </tbody>
    </table>
</body>
</html>';

// --- Processo de Geração do PDF ---

// Carrega o autoload do composer para encontrar a classe Dompdf
require __DIR__ . '/../../../vendor/autoload.php';

// Configurações do Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

// Instancia o Dompdf
$dompdf = new Dompdf($options);

// Carrega o HTML no Dompdf
$dompdf->loadHtml($html);

// Define o tamanho e a orientação do papel
$dompdf->setPaper('A4', 'portrait'); // A4 em modo retrato

// Renderiza o HTML para PDF
$dompdf->render();

// Envia o PDF para o navegador
// O "false" no final faz com que o PDF seja exibido na tela em vez de baixar automaticamente
$dompdf->stream("relatorio_produtos.pdf", ["Attachment" => false]);