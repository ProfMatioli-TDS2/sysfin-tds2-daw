<?php include __DIR__ . '/../layout/header.php'; ?>

<style>
    form {
        margin-bottom: 20px;
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    form label {
        font-weight: 600;
    }

    form input[type="date"] {
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    form button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
    }

    form button:hover {
        background-color: #0056b3;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 0.95rem;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    thead th {
        background-color: #f4f4f4;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    tbody tr:nth-child(even) {
        background-color: #fafafa;
    }

    tbody tr:hover {
        background-color: #f1f7ff;
    }

    .entrada {
        color: green;
        font-weight: 600;
    }

    .saida {
        color: red;
        font-weight: 600;
    }

    .totais {
        margin-top: 20px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    .saldo {
        font-size: 1.1em;
        font-weight: bold;
        margin-top: 10px;
    }

    h1 {
        margin-bottom: 15px;
    }

    h3 {
        margin-top: 20px;
    }
</style>

<h1>Relatório de Movimentações</h1>

<form method="get" action="">
    <label>Data Inicial:</label>
    <input type="date" name="data_inicial" value="<?= htmlspecialchars($_GET['data_inicial'] ?? '') ?>">

    <label>Data Final:</label>
    <input type="date" name="data_final" value="<?= htmlspecialchars($_GET['data_final'] ?? '') ?>">

    <button type="submit">Filtrar</button>
</form>

<?php if (!empty($_GET['data_inicial']) && !empty($_GET['data_final'])): ?>
    <h3>Saldo Anterior: 
        <span style="color:#333;">R$ <?= number_format($saldoAnterior, 2, ',', '.') ?></span>
    </h3>

    <table>
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
                <tr>
                    <td><?= date('d/m/Y', strtotime($m['data_movimento'])) ?></td>
                    <td><?= htmlspecialchars($m['descricao']) ?></td>
                    <td class="<?= $m['tipo'] === 'E' ? 'entrada' : 'saida' ?>">
                        <?= $m['tipo'] === 'E' ? 'Entrada' : 'Saída' ?>
                    </td>
                    <td class="<?= $m['tipo'] === 'E' ? 'entrada' : 'saida' ?>">
                        <?= number_format($m['valor'], 2, ',', '.') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php $saldoFinal = $saldoAnterior + $totais['entradas'] - $totais['saidas']; ?>

    <div class="totais">
        <h3>Totais do Período:</h3>
        <p>Total de Entradas: <span class="entrada">R$ <?= number_format($totais['entradas'], 2, ',', '.') ?></span></p>
        <p>Total de Saídas: <span class="saida">R$ <?= number_format($totais['saidas'], 2, ',', '.') ?></span></p>
        <p class="saldo">Saldo Final: 
            <span style="color: <?= $saldoFinal >= 0 ? 'green' : 'red' ?>;">
                R$ <?= number_format($saldoFinal, 2, ',', '.') ?>
            </span>
        </p>
    </div>

<?php else: ?>
    <p>Escolha uma data inicial e final para gerar o relatório.</p>
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>
