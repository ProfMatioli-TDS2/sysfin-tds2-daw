<h2>Lista de Fornecedores</h2>

<form method="GET" action="/fornecedores">
    <input type="text" name="busca" placeholder="Buscar por nome">
    <button type="submit">Buscar</button>
</form>

<a href="/fornecedores/novo">Novo Fornecedor</a>
<a href="/fornecedores/relatorio" target="_blank">Gerar Relatório PDF</a>

<table border="1">
    <tr>
        <th>Nome</th>
        <th>CNPJ</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($fornecedores as $f): ?>
    <tr>
        <td><?= htmlspecialchars($f->nome) ?></td>
        <td><?= htmlspecialchars($f->cnpj) ?></td>
        <td><?= htmlspecialchars($f->email) ?></td>
        <td><?= htmlspecialchars($f->telefone) ?></td>
        <td>
            <a href="/fornecedores/editar/<?= $f->id ?>">Editar</a> |
            <a href="/fornecedores/excluir/<?= $f->id ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
