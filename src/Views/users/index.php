<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h1 class="my-4">Gestão de Usuários</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <a href="<?php echo BASE_URL; ?>/index.php?url=/users/criar" class="btn btn-success mb-3">
        Novo Usuário
    </a>

    <table class="table table-striped table-bordered">
        <thead class="table-light">
            <tr>
                <th>Nome</th>
                <th>Login</th>
                <th>Perfis Associados</th>
                <th class="text-center">Ativo</th>
                <th style="width: 180px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario->nome); ?></td>
                    <td><?php echo htmlspecialchars($usuario->login); ?></td>
                    <td><?php echo htmlspecialchars($usuario->perfis_nomes ?? 'Nenhum'); ?></td>
                    <td class="text-center"><?php echo $usuario->ativo ? 'Sim' : 'Não'; ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=/users/editar/<?php echo $usuario->id; ?>" 
                           class="btn btn-warning btn-sm">Editar</a>
                        
                        <?php 
                        // Proteção visual:
                        // 1. O usuário ID 1 não pode ser excluído.
                        // 2. O usuário logado não pode excluir a si mesmo.
                        if ($usuario->id > 1 && $usuario->id != \App\Core\SessionManager::getUserId()): 
                        ?>
                            <form method="POST" 
                                  action="<?php echo BASE_URL; ?>/index.php?url=/users/delete/<?php echo $usuario->id; ?>" 
                                  style="display: inline;"
                                  onsubmit="return confirm('Tem certeza que deseja excluir?');">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Excluir
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>