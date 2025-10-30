<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Relatório de Vendas (<?= htmlspecialchars($_POST['data_inicial']) ?> até <?= htmlspecialchars($_POST['data_final']) ?>)</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Data da Venda</th>
                <th>Valor Total (R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($vendas)): ?>
                <tr><td colspan="4">Nenhuma venda encontrada neste período.</td></tr>
            <?php else: ?>
                <?php foreach ($vendas as $v): ?>
                    <tr>
                        <td><?= $v['id'] ?></td>
                        <td><?= htmlspecialchars($v['cliente']) ?></td>
                        <td><?= date('d/m/Y', strtotime($v['data_venda'])) ?></td>
                        <td><?= number_format($v['valor_total'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
