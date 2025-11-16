<!-- 
Este arquivo é apenas um template HTML. 
O Controller é quem o transforma em PDF.
-->
<style>
    body { font-family: sans-serif; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    h2 { text-align: center; }
    .header { text-align: center; margin-bottom: 20px; }
    .footer { text-align: right; font-size: 0.8em; margin-top: 20px; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
</style>

<div class="header">
    <strong>Sistema SysFin - Gestão Financeira</strong>
</div>

<h2>Relatório de Produtos</h2>

<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th class="text-right">Preço Venda (R$)</th>
            <th class="text-center">Est. Mínimo</th>
            <th class="text-center">Est. Atual</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= htmlspecialchars($produto->nome) ?></td>
                <td><?= htmlspecialchars($produto->descricao) ?></td>
                <td class="text-right"><?= number_format($produto->preco_venda, 2, ',', '.') ?></td>
                <td class="text-center"><?= htmlspecialchars($produto->estoque_minimo) ?></td>
                <td class="text-center"><?= htmlspecialchars($produto->estoque) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="footer">
    Gerado em: <?php echo date('d/m/Y H:i:s'); ?>
</div>