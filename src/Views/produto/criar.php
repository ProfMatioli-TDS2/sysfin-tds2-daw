<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>Adicionar Novo Produto</h1>

<form method="POST" action="<?= BASE_URL ?>/index.php?url=/produtos/criar" class="mt-4">
    <div class="mb-3">
        
        <label for="nome" class="form-label">Nome do Produto:</label>
        <input type="text" name="nome" id="nome" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição:</label>
        <textarea name="descricao" id="descricao" class="form-control" rows="3"></textarea>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="preco_venda" class="form-label">Preço de Venda (R$):</label>
            <input type="number" name="preco_venda" id="preco_venda" class="form-control" step="0.01" min="0" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="estoque" class="form-label">Estoque Inicial:</label>
            <input type="number" name="estoque" id="estoque" class="form-control" min="0" required>
        </div>
        
        <div class="col-md-4 mb-3">
        <label for="estoque_minimo" class="form-label">Estoque Mínimo:</label>
        <input type="number" name="estoque_minimo" id="estoque_minimo" class="form-control" min="0" value="0" required>
    </div>
    </div>
    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Salvar Produto</button>
        <a href="<?= BASE_URL ?>/index.php?url=/produtos" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>