<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Lista de Fornecedores</h1>
    <div>
        <a class="btn btn-primary me-2" href="/fornecedores/criar">Novo Fornecedor</a>
        <a class="btn btn-secondary" href="/fornecedores/relatorio" target="_blank">Gerar Relatório PDF</a>
    </div>
</div>

<form method="GET" action="/fornecedores" class="mb-4">
    <div class="input-group w-50">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por nome">
        <button type="submit" class="btn btn-outline-primary">Buscar</button>
        <a href="/fornecedores" class="btn btn-outline-secondary">Atualizar</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-bordered table-hover w-100 mx-auto">
        <thead class="table-light">
            <tr>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fornecedores as $f): ?>
                <tr>
                    <td><?= htmlspecialchars($f->nome) ?></td>
                    <td><?= htmlspecialchars($f->cnpj) ?></td>
                    <td><?= htmlspecialchars($f->email) ?></td>
                    <td><?= htmlspecialchars($f->telefone) ?></td>
                    <td>
                        <a class="btn btn-warning btn-sm" href="/fornecedores/editar/<?= $f->id ?>">Editar</a>
                        <form method="POST" action="/fornecedores/excluir/<?= $f->id ?>" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>