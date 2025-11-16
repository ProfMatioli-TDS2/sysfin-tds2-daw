<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h1 class="my-4">Gestão de Perfis</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <a href="<?php echo BASE_URL; ?>/index.php?url=/profiles/criar" class="btn btn-success mb-3">
        Novo Perfil
    </a>

    <table class="table table-striped table-bordered">
        <thead class="table-light">
            <tr>
                <th>Nome</th>
                <th style="width: 180px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($perfis as $perfil): ?>
                <tr>
                    <td><?php echo htmlspecialchars($perfil->nome); ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=/profiles/editar/<?php echo $perfil->id; ?>" 
                           class="btn btn-warning btn-sm">Editar</a>
                        
                        <?php if ($perfil->id > 3): // Protege os perfis 1, 2, 3 ?>
                        <a href="<?php echo BASE_URL; ?>/index.php?url=/profiles/delete/<?php echo $perfil->id; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>