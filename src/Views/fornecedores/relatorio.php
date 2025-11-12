<?php
use Dompdf\Dompdf;
require __DIR__ . '/../../../vendor/autoload.php';

$html = '
<table width="100%">
    <tr>
        <td align="center"><strong>Sistema SysFin - Gestão Financeira</strong></td>
    </tr>
</table>

<h2 align="center">Relatório de Fornecedores</h2>

<table border="1" width="100%" cellspacing="0" cellpadding="5">
    <tr>
        <th>Nome</th>
        <th>CNPJ</th>
        <th>Email</th>
        <th>Telefone</th>
    </tr>';

foreach ($fornecedores as $f) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($f->nome) . '</td>';
    $html .= '<td>' . htmlspecialchars($f->cnpj) . '</td>';
    $html .= '<td>' . htmlspecialchars($f->email) . '</td>';
    $html .= '<td>' . htmlspecialchars($f->telefone) . '</td>';
    $html .= '</tr>';
}

$html .= '</table>';

// Rodapé com data e hora no canto inferior direito
date_default_timezone_set('America/Sao_Paulo');

$html .= '
<br><br><table width="100%">
    <tr>
        <td align="right">Gerado em: ' . date('d/m/Y H:i:s') . '</td>
    </tr>
</table>';


$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_fornecedores.pdf", ["Attachment" => false]);
