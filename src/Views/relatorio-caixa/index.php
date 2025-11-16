<?php 
require __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <h1 class="my-4">Relatório de Fluxo de Caixa</h1>

    <!-- REQUISITO #1: Filtros de Data -->
    <form method="POST" action="<?php echo BASE_URL; ?>/index.php?url=/relatorio-movimento-caixa">
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
    
        <h3 class="mt-4">Relatório para o período de <?php echo htmlspecialchars(date('d/m/Y', strtotime($dataInicial))); ?> a <?php echo htmlspecialchars(date('d/m/Y', strtotime($dataFinal))); ?></h3>

        <!-- REQUISITO #2a: Saldo Anterior -->
        <div class="card bg-light p-3 mb-3">
            <h4 class="h5">Saldo Anterior (até <?php echo htmlspecialchars(date('d/m/Y', strtotime("-1 day", strtotime($dataInicial)))); ?>)</h4>
            <p class="fs-4 fw-bold mb-0">R$ <?php echo number_format($saldoAnterior, 2, ',', '.'); ?></p>
        </div>

        <!-- REQUISITO #2b: Lista de Movimentações -->
        <h4 class="h5 mt-4">Movimentações no Período</h4>
        <table class="table table-striped table-bordered">
            <thead class="table-light">
                <tr>
                    <th style="width: 150px;">Data</th>
                    <th>Descrição</th>
                    <th class="text-end" style="width: 150px;">Valor (R$)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($movimentacoes)): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Nenhuma movimentação no período.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($movimentacoes as $mov): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($mov['data_movimento']))); ?></td>
                            <td><?php echo htmlspecialchars($mov['descricao']); ?></td>
                            
                            <!-- REQUISITO #2b: Diferenciar Entradas/Saídas -->
                            <?php if ($mov['tipo'] == 'E'): ?>
                                <td class="text-end text-success fw-bold">
                                    + <?php echo number_format($mov['valor'], 2, ',', '.'); ?>
                                </td>
                            <?php else: ?>
                                <td class="text-end text-danger fw-bold">
                                    - <?php echo number_format($mov['valor'], 2, ',', '.'); ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- REQUISITO #2c: Resumo do Período -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="card-title">Resumo do Período</h5>
                    <p class="card-text text-success mb-0">
                        <strong>Total de Entradas:</strong> R$ <?php echo number_format($totalEntradas, 2, ',', '.'); ?>
                    </p>
                    <p class="card-text text-danger">
                        <strong>Total de Saídas:</strong> R$ <?php echo number_format($totalSaidas, 2, ',', '.'); ?>
                    </p>
                </div>
            </div>
            
            <!-- REQUISITO #2c: Saldo Final -->
            <div class="col-md-6">
                <div class="card p-3 <?php echo ($saldoFinal >= 0) ? 'bg-success-subtle' : 'bg-danger-subtle'; ?>">
                    <h5 class="card-title">Saldo Final</h5>
                    <p class="fs-4 fw-bold mb-0">
                        R$ <?php echo number_format($saldoFinal, 2, ',', '.'); ?>
                    </p>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>