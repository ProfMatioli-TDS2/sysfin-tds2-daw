<?php
use Dompdf\Dompdf;
use Dompdf\Options;

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

require __DIR__ . '/../../../vendor/autoload.php';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_produtos.pdf", ["Attachment" => false]);