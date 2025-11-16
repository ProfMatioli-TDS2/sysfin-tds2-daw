<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Lista de Fornecedores</h1>
    <div>
        <!-- CORRIGIDO -->
        <a class="btn btn-primary me-2" href="<?php echo BASE_URL; ?>/index.php?url=/fornecedores/criar">Novo Fornecedor</a>
    </div>
</div>

<!-- CORRIGIDO -->
<form method="GET" action="<?php echo BASE_URL; ?>/index.php?url=/fornecedores" class="mb-4">
    <div class="input-group w-50">
        <!-- Adicionado input hidden para a URL -->
        <input type="hidden" name="url" value="/fornecedores">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por nome" value="<?php echo htmlspecialchars($_GET['busca'] ?? ''); ?>">
        <button type="submit" class="btn btn-outline-primary">Buscar</button>
        <!-- CORRIGIDO -->
        <a href="<?php echo BASE_URL; ?>/index.php?url=/fornecedores" class="btn btn-outline-secondary">Atualizar</a>
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
                        <!-- CORRIGIDO -->
                        <a class="btn btn-warning btn-sm" href="<?php echo BASE_URL; ?>/index.php?url=/fornecedores/editar/<?= $f->id ?>">Editar</a>
                        
                        <!-- CORRIGIDO -->
                        <form method="POST" action="<?php echo BASE_URL; ?>/index.php?url=/fornecedores/excluir/<?= $f->id ?>" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>