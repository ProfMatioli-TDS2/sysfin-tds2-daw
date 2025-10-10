<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>Editar Produto</h1>

<form method="POST" action="<?= BASE_URL ?>/index.php?url=/produtos/editar/<?= $produto->id ?>" class="mt-4">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome do Produto:</label>
        <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($produto->nome) ?>" required>
    </div>
    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição:</label>
        <textarea name="descricao" id="descricao" class="form-control" rows="3"><?= htmlspecialchars($produto->descricao) ?></textarea>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="preco_venda" class="form-label">Preço de Venda (R$):</label>
            <input type="number" name="preco_venda" id="preco_venda" class="form-control" step="0.01" min="0" value="<?= $produto->preco_venda ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="estoque" class="form-label">Estoque Atual:</label>
            <input type="number" name="estoque" id="estoque" class="form-control" value="<?= $produto->estoque ?>" readonly>
            <div class="form-text">O estoque não pode ser editado manualmente. Ele é atualizado pelas rotinas de compra e venda.</div>
        </div>
    </div>
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Atualizar Produto</button>
        <a href="<?= BASE_URL ?>/index.php?url=/produtos" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>