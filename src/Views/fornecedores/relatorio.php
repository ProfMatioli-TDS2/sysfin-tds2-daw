<?php
use Dompdf\Dompdf;

$html = '<h2>Relatório de Fornecedores</h2>';
$html .= '<table border="1" width="100%" cellspacing="0" cellpadding="5">';
$html .= '<tr><th>Nome</th><th>CNPJ</th><th>Email</th><th>Telefone</th></tr>';

foreach ($fornecedores as $f) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($f->nome) . '</td>';
    $html .= '<td>' . htmlspecialchars($f->cnpj) . '</td>';
    $html .= '<td>' . htmlspecialchars($f->email) . '</td>';
    $html .= '<td>' . htmlspecialchars($f->telefone) . '</td>';
    $html .= '</tr>';
}

$html .= '</table>';

require __DIR__ . '/../../../vendor/autoload.php';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_fornecedores.pdf", ["Attachment" => false]);
