<?php include __DIR__ . '/../layout/header.php'; ?>

<h1>Relatório de Movimentações</h1>

<form method="get" action="">
    <label>Data Inicial:</label>
    <input type="date" name="data_inicial" value="<?= htmlspecialchars($_GET['data_inicial'] ?? '') ?>">

    <label>Data Final:</label>
    <input type="date" name="data_final" value="<?= htmlspecialchars($_GET['data_final'] ?? '') ?>">

    <button type="submit">Filtrar</button>
</form>

<?php if (!empty($_GET['data_inicial']) && !empty($_GET['data_final'])): ?>
    <h3>Saldo Anterior: R$ <?= number_format($saldoAnterior, 2, ',', '.') ?></h3>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Valor (R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimentacoes as $m): ?>
                <tr style="color: <?= $m['tipo'] === 'E' ? 'green' : 'red' ?>;">
                    <td><?= date('d/m/Y', strtotime($m['data_movimento'])) ?></td>
                    <td><?= htmlspecialchars($m['descricao']) ?></td>
                    <td><?= $m['tipo'] === 'E' ? 'Entrada' : 'Saída' ?></td>
                    <td><?= number_format($m['valor'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
        $saldoFinal = $saldoAnterior + $totais['entradas'] - $totais['saidas'];
    ?>

    <h3>Totais do Período:</h3>
    <p>Total de Entradas: <strong style="color:green;">R$ <?= number_format($totais['entradas'], 2, ',', '.') ?></strong></p>
    <p>Total de Saídas: <strong style="color:red;">R$ <?= number_format($totais['saidas'], 2, ',', '.') ?></strong></p>
    <p><strong>Saldo Final: R$ <?= number_format($saldoFinal, 2, ',', '.') ?></strong></p>

<?php else: ?>
    <p>Escolha uma data inicial e final para gerar o relatório.</p>
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>
