<h2>Editar Fornecedor</h2>

<form method="POST" action="/fornecedores/editar/<?= $fornecedor->id ?>">
    <label>Nome:</label><br>
    <input type="text" name="nome" value="<?= htmlspecialchars($fornecedor->nome) ?>" required><br>

    <label>CNPJ:</label><br>
    <input type="text" name="cnpj" value="<?= htmlspecialchars($fornecedor->cnpj) ?>" required><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?= htmlspecialchars($fornecedor->email) ?>"><br>

    <label>Telefone:</label><br>
    <input type="text" name="telefone" value="<?= htmlspecialchars($fornecedor->telefone) ?>"><br>

    <button type="submit">Atualizar</button>
</form>
