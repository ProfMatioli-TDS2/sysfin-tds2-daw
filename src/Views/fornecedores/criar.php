<h2>Cadastrar Fornecedor</h2>

<form method="POST" action="/fornecedores/novo">
    <label>Nome:</label><br>
    <input type="text" name="nome" required><br>

    <label>CNPJ:</label><br>
    <input type="text" name="cnpj" required><br>

    <label>Email:</label><br>
    <input type="email" name="email"><br>

    <label>Telefone:</label><br>
    <input type="text" name="telefone"><br>

    <button type="submit">Salvar</button>
</form>
