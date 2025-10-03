<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestão de Produtos</h1>
    <a class="btn btn-primary" href="/produtos/criar">Adicionar Produto</a>
</div>

<form method="GET" action="/produtos" class="mb-4">
    <div class="input-group">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por nome do produto..." value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
        <button class="btn btn-secondary" type="submit">Buscar</button>
    </div>
</form>

<div class="mb-3">
    <a href="/produtos/relatorio" target="_blank" class="btn btn-info">Gerar Relatório PDF</a>
</div>


<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço de Venda</th>
                <th>Estoque Atual</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= $produto->id ?></td>
                    <td><?= htmlspecialchars($produto->nome) ?></td>
                    <td>R$ <?= number_format($produto->preco_venda, 2, ',', '.') ?></td>
                    <td><?= $produto->estoque ?></td>
                    <td>
                        <a class="btn btn-warning btn-sm" href="/produtos/editar/<?= $produto->id ?>">Editar</a>
                        <a class="btn btn-danger btn-sm" href="/produtos/excluir/<?= $produto->id ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>