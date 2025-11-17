<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h1 class="my-4">Plano de Contas</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (\App\Core\SessionManager::hasRole('Administrador')): ?>
        <a href="<?php echo BASE_URL; ?>/index.php?url=/plano-contas/criar" class="btn btn-success mb-3">
            Nova Conta
        </a>
    <?php endif; ?>

    <table class="table table-striped table-bordered">
        <thead class="table-light">
            <tr>
                <th>Descrição</th>
                <th style="width: 150px;">Tipo</th>
                <th style="width: 180px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($planosConta as $plano): ?>
                <tr>
                    <td><?php echo htmlspecialchars($plano->descricao); ?></td>
                    <td>
                        <?php 
                            // Converte 'R'/'D' para texto
                            if ($plano->tipo === 'R') {
                                echo '<span class="text-success fw-bold">Receita</span>';
                            } else {
                                echo '<span class="text-danger fw-bold">Despesa</span>';
                            }
                        ?>
                    </td>
                    <td>
                        <?php if (\App\Core\SessionManager::hasRole('Administrador')): ?>
                            <a href="<?php echo BASE_URL; ?>/index.php?url=/plano-contas/editar/<?php echo $plano->id; ?>" 
                               class="btn btn-warning btn-sm">
                                Editar
                            </a>

                            <?php if ($plano->id > 5): // Só mostra o botão de excluir para contas não-padrão ?>
                                <form method="POST" 
                                      action="<?php echo BASE_URL; ?>/index.php?url=/plano-contas/excluir/<?php echo $plano->id; ?>" 
                                      style="display: inline;"
                                      onsubmit="return confirm('Tem certeza que deseja excluir? Esta ação não pode ser desfeita.');">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Excluir
                                    </button>
                                </form>
                            <?php endif; ?>
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