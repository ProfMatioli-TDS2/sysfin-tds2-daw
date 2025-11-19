<style>
    body { font-family: sans-serif; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    h2 { text-align: center; }
    .header { text-align: center; margin-bottom: 20px; }
    .footer { text-align: right; font-size: 0.8em; margin-top: 20px; }
    .receita { color: #0a6b0a; }
    .despesa { color: #d9534f; }
</style>

<div class="header">
    <strong>Sistema SysFin - Gestão Financeira</strong>
</div>

<h2>Relatório - Plano de Contas</h2>

<table>
    <thead>
        <tr>
            <th>Descrição</th>
            <th style="width: 120px;">Tipo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($planosConta as $plano): ?>
            <tr>
                <td><?= htmlspecialchars($plano->descricao) ?></td>
                <td>
                    <?php if ($plano->tipo === 'R'): ?>
                        <strong class="receita">Receita</strong>
                    <?php else: ?>
                        <strong class="despesa">Despesa</strong>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="footer">
    Gerado em: <?php echo date('d/m/Y H:i:s'); ?>
</div>