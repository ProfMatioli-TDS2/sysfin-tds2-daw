<?php require __DIR__ . '/../layout/header.php'; ?>

<h2><?= isset($cliente) ? 'Editar Cliente' : 'Novo Cliente' ?></h2>

<form method="POST" action="/clientes/<?= isset($cliente) ? 'editar/' . $cliente->id : 'novo' ?>">
    <div class="form-group mb-3">
        <label for="nome">Nome</label>
        <input type="text" name="nome" id="nome" class="form-control"
               value="<?= htmlspecialchars($cliente->nome ?? '') ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="cpf_cnpj">CPF/CNPJ</label>
        <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="form-control"
               value="<?= htmlspecialchars($cliente->cpf_cnpj ?? '') ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" class="form-control"
               value="<?= htmlspecialchars($cliente->email ?? '') ?>">
    </div>

    <div class="form-group mb-3">
        <label for="telefone">Telefone</label>
        <input type="text" name="telefone" id="telefone" class="form-control"
               value="<?= htmlspecialchars($cliente->telefone ?? '') ?>">
    </div>

    <a href="/clientes" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Salvar</button>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>