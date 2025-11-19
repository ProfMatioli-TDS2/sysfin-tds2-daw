<?php 
require __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Vendas Realizadas</h1>
        <a class="btn btn-primary" href="<?php echo BASE_URL; ?>/index.php?url=/vendas/create">
            Nova Venda
        </a>
    </div>

    <table class="table table-striped table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID da Venda</th>
                <th>Data</th>
                <th>Cliente</th>
                <th class="text-end">Valor Total</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($vendas)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">Nenhuma venda registrada.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($vendas as $venda): ?>
                    <tr>
                        <td><?php echo $venda['id']; ?></td>
                        <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($venda['data_venda']))); ?></td>
                        <td><?php echo htmlspecialchars($venda['nome_cliente']); ?></td>
                        <td class="text-end">R$ <?php echo number_format($venda['valor_total'], 2, ',', '.'); ?></td>
                        <td class="text-center">
                            <a href="<?php echo BASE_URL; ?>/index.php?url=/vendas/details/<?php echo $venda['id']; ?>" class="btn btn-info btn-sm">
                                Detalhes
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php 
require __DIR__ . '/../layout/footer.php'; 
?>