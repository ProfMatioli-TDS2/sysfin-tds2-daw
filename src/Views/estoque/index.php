<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Relatório de Estoque</h1>
</div>

<section class="Tabela">
    <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço de venda</th>
                <th>Estoque atual</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= $produto['id']; ?></td>
                <td><?= $produto['nome']; ?></td>
                <td>R$<?= number_format($produto['preco_venda'], 2, ',', '.'); ?></td>
                <td><?= $produto['estoque_atual']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
