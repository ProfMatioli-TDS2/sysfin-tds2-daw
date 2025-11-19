<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h1 class="my-4">
        <?php echo isset($perfil) ? 'Editar Perfil' : 'Novo Perfil'; ?>
    </h1>

    <?php
    $actionUrl = isset($perfil)
        ? BASE_URL . '/index.php?url=/profiles/editar/' . $perfil->id
        : BASE_URL . '/index.php?url=/profiles/criar';
    
    $nome = $perfil->nome ?? '';
    ?>

    <form method="POST" action="<?php echo $actionUrl; ?>">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Perfil</label>
            <input type="text" class="form-control" id="nome" name="nome" 
                   value="<?php echo htmlspecialchars($nome); ?>" required>
        </div>
        
        <a href="<?php echo BASE_URL; ?>/index.php?url=/profiles" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>