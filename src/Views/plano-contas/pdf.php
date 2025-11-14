<?php

use Dompdf\Dompdf;
use Dompdf\Options;

require __DIR__ . '/../../vendor/autoload.php';

// Carrega os dados do banco
$pdo = \App\Core\Database::getConnection();
$stmt = $pdo->query("SELECT * FROM plano_de_contas ORDER BY tipo, descricao");
$planosConta = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gera o HTML do relatório
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório - Plano de Contas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; }
        th { background: #eee; padding: 6px; }
        td { padding: 6px; }
    </style>
</head>
<body>

<h2>Relatório de Plano de Contas</h2>

<table>
    <thead>
        <tr>
            <th>Descrição</th>
            <th>Tipo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($planosConta as $plano): ?>
            <tr>
                <td><?= htmlspecialchars($plano["descricao"]) ?></td>
                <td><?= $plano["tipo"] === "R" ? "Receita" : "Despesa" ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
<?php
$html = ob_get_clean();

// Configura o PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Exibe o PDF no navegador
$dompdf->stream("relatorio_plano_de_contas.pdf", ["Attachment" => false]);
exit;
