<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Lista de Fornecedores</h1>
    <div>
        <?php if (\App\Core\SessionManager::hasRole('Administrador')): ?>
            <a class="btn btn-primary me-2" href="<?php echo BASE_URL; ?>/index.php?url=/fornecedores/criar">Novo Fornecedor</a>
        <?php endif; ?>
    </div>
</div>

<form method="GET" action="<?php echo BASE_URL; ?>/index.php?url=/fornecedores" class="mb-4">
    <div class="input-group w-50">
        <input type="hidden" name="url" value="/fornecedores">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por nome" value="<?php echo htmlspecialchars($_GET['busca'] ?? ''); ?>">
        <button type="submit" class="btn btn-outline-primary">Buscar</button>
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
                        <?php if (\App\Core\SessionManager::hasRole('Administrador')): ?>
                            <a class="btn btn-warning btn-sm" href="<?php echo BASE_URL; ?>/index.php?url=/fornecedores/editar/<?= $f->id ?>">Editar</a>
                            
                            <form method="POST" action="<?php echo BASE_URL; ?>/index.php?url=/fornecedores/excluir/<?= $f->id ?>" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted">Acesso restrito</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>