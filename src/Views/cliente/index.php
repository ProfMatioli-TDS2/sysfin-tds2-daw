<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h1 class="my-4">Lista de Clientes</h1>

    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="hidden" name="url" value="/clientes">
            <input type="text" class="form-control" 
                   name="busca" 
                   placeholder="Buscar cliente por nome..."
                   value="<?php echo htmlspecialchars($nome ?? '', ENT_QUOTES); ?>">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>
    </form>

    <?php if (\App\Core\SessionManager::hasRole('Administrador')): ?>
        <a href="<?php echo BASE_URL; ?>/index.php?url=/clientes/criar" class="btn btn-success mb-3">
            Novo Cliente
        </a>
    <?php endif; ?>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF/CNPJ</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th style="width: 180px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cliente->nome); ?></td>
                    <td><?php echo htmlspecialchars($cliente->cpf_cnpj); ?></td>
                    <td><?php echo htmlspecialchars($cliente->email); ?></td>
                    <td><?php echo htmlspecialchars($cliente->telefone); ?></td>
                    <td>
                        <?php if (\App\Core\SessionManager::hasRole('Administrador')): ?>
                            <a href="<?php echo BASE_URL; ?>/index.php?url=/clientes/editar/<?php echo $cliente->id; ?>" 
                               class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            <form method="POST" 
                                  action="<?php echo BASE_URL; ?>/index.php?url=/clientes/excluir/<?php echo $cliente->id; ?>" 
                                  style="display: inline;"
                                  onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Excluir
                                </button>
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