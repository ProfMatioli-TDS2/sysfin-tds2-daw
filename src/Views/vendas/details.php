<?php 
require __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <h1 class="my-4">Detalhes da Venda #<?php echo $venda['id']; ?></h1>

    <div class="card mb-4">
        <div class="card-header">
            Informações da Venda
        </div>
        <div class="card-body">
            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($venda['nome_cliente']); ?></p>
            <p><strong>Data da Venda:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($venda['data_venda']))); ?></p>
            <p class="fs-5 fw-bold">
                <strong>Valor Total:</strong> R$ <?php echo number_format($venda['valor_total'], 2, ',', '.'); ?>
            </p>
        </div>
    </div>

    <h2 class="h4">Itens Inclusos</h2>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Produto</th>
                <th class="text-center">Quantidade</th>
                <th class="text-end">Valor Unitário</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($venda['itens'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nome_produto']); ?></td>
                    <td class="text-center"><?php echo $item['quantidade']; ?></td>
                    <td class="text-end">R$ <?php echo number_format($item['valor_unitario'], 2, ',', '.'); ?></td>
                    <td class="text-end">R$ <?php echo number_format($item['quantidade'] * $item['valor_unitario'], 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?php echo BASE_URL; ?>/index.php?url=/vendas" class="btn btn-primary mt-3">
        Voltar para a Lista
    </a>

</div>

<?php 
require __DIR__ . '/../layout/footer.php'; 
?>