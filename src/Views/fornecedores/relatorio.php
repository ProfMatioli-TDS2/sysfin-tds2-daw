<!-- 
Este arquivo agora é apenas um template HTML. 
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
</style>

<div class="header">
    <strong>Sistema SysFin - Gestão Financeira</strong>
</div>

<h2>Relatório de Fornecedores</h2>

<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>CNPJ</th>
            <th>Email</th>
            <th>Telefone</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($fornecedores as $f): ?>
            <tr>
                <td><?= htmlspecialchars($f->nome) ?></td>
                <td><?= htmlspecialchars($f->cnpj) ?></td>
                <td><?= htmlspecialchars($f->email) ?></td>
                <td><?= htmlspecialchars($f->telefone) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="footer">
    Gerado em: <?php echo date('d/m/Y H:i:s'); ?>
</div>