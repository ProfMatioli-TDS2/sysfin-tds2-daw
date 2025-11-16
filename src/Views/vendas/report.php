<?php 
require __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <h1 class="my-4">Relatório de Vendas por Período</h1>

    <!-- REQUISITO #1: Filtros de Data -->
    <form method="POST" action="<?php echo BASE_URL; ?>/index.php?url=/vendas/relatorio">
        <div class="row g-3 align-items-end p-3 bg-light border rounded mb-4">
            <div class="col-md-5">
                <label for="data_inicial" class="form-label">Data Inicial:</label>
                <input type="date" id="data_inicial" name="data_inicial" class="form-control"
                       value="<?= htmlspecialchars($dataInicial) ?>" required>
            </div>
            <div class="col-md-5">
                <label for="data_final" class="form-label">Data Final:</label>
                <input type="date" id="data_final" name="data_final" class="form-control"
                       value="<?= htmlspecialchars($dataFinal) ?>" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Gerar</button>
            </div>
        </div>
    </form>

    <?php if ($hasData): // Só exibe se o formulário foi enviado ?>
    
        <h3 class="mt-4">Resultado para o período de <?php echo htmlspecialchars(date('d/m/Y', strtotime($dataInicial))); ?> a <?php echo htmlspecialchars(date('d/m/Y', strtotime($dataFinal))); ?></h3>

        <!-- REQUISITO #2: Lista de Vendas -->
        <table class="table table-striped table-bordered mt-3">
            <thead class="table-light">
                <tr>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th class="text-end">Valor Total (R$)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($vendas)): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Nenhuma venda encontrada no período.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($vendas as $venda): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($venda['data_venda']))); ?></td>
                            <td><?php echo htmlspecialchars($venda['cliente']); // O Model já busca o nome do cliente ?></td>
                            <td class="text-end">R$ <?php echo number_format($venda['valor_total'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- REQUISITO #3: Resumo (Totais) -->
        <div class="card bg-light p-3 mt-4">
            <h4 class="h5">Resumo do Período</h4>
            <p class="mb-1">
                <strong>Número Total de Vendas:</strong> <?php echo $totalVendas; ?>
            </p>
            <p class="mb-0">
                <strong>Valor Total Vendido:</strong> R$ <?php echo number_format($valorTotalVendido, 2, ',', '.'); ?>
            </p>
        </div>

    <?php endif; ?>
</div>

<?php 
require __DIR__ . '/../layout/footer.php'; 
?>