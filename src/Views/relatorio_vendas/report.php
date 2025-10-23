<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Vendas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h2>Relatório de Vendas</h2>
    <p>Período: <?= htmlspecialchars($dataInicio) ?> a <?= htmlspecialchars($dataFim) ?></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Cliente</th>
                <th>Valor Total (R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($vendas)): ?>
                <?php foreach ($vendas as $v): ?>
                    <tr>
                        <td><?= $v['id'] ?></td>
                        <td><?= date('d/m/Y', strtotime($v['data_venda'])) ?></td>
                        <td><?= htmlspecialchars($v['cliente']) ?></td>
                        <td><?= number_format($v['valor_total'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhuma venda encontrada no período informado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
