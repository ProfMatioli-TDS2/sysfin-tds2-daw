<?php 
// O Controller já chama 'extract($data)', então as variáveis estão disponíveis
include __DIR__ . '/../layout/header.php'; 

// Define os valores padrão (o seu código já fazia isso, está ótimo)
$compras = $compras ?? [];
$totalCompras = $totalCompras ?? 0;
$valorTotal = $valorTotal ?? 0.00;
$dataInicial = $dataInicial ?? '';
$dataFinal = $dataFinal ?? '';
?>

<h2>Relatório de Compras por Período</h2>

<!-- Formulário com classes Bootstrap -->
<form method="POST" action="<?php echo BASE_URL; ?>/index.php?url=/compras/relatorio">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label for="data_inicial" class="form-label">Data Inicial:</label>
            <input type="date" id="data_inicial" name="data_inicial" class="form-control"
                   value="<?= htmlspecialchars($dataInicial) ?>" required>
        </div>

        <div class="col-md-4">
            <label for="data_final" class="form-label">Data Final:</label>
            <input type="date" id="data_final" name="data_final" class="form-control"
                   value="<?= htmlspecialchars($dataFinal) ?>" required>
        </div>
        
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">
                Gerar Relatório
            </button>
        </div>
    </div>
</form>

<hr class="my-4">

<?php if (!empty($compras)): ?>
    
    <h3>Resultado para o Período de <?= htmlspecialchars(date('d/m/Y', strtotime($dataInicial))) ?> a <?= htmlspecialchars(date('d/m/Y', strtotime($dataFinal))) ?></h3>
    
    <!-- Tabela com classes Bootstrap -->
    <table class="table table-striped table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>Data</th>
                <th>Fornecedor</th>
                <th class="text-end">Valor Total da Compra</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($compras as $compra): ?>
                <tr>
                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($compra['data_compra']))) ?></td>
                    <td><?= htmlspecialchars($compra['nome_fornecedor']) ?></td>
                    <td class="text-end">R$ <?= number_format($compra['valor_total'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Resumo com classes Bootstrap -->
    <div class="card bg-light p-3 mt-4">
        <h4 class="h5">Resumo do Período</h4>
        <p class="mb-1">
            <strong>Número Total de Compras:</strong> <?= $totalCompras ?>
        </p>
        <p class="mb-0">
            <strong>Valor Total Comprado:</strong> R$ <?= number_format($valorTotal, 2, ',', '.') ?>
        </p>
    </div>
    
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    
    <!-- Alerta com classes Bootstrap -->
    <div class="alert alert-warning mt-4">
        Nenhuma compra encontrada no período selecionado (<?= htmlspecialchars(date('d/m/Y', strtotime($dataInicial))) ?> a <?= htmlspecialchars(date('d/m/Y', strtotime($dataFinal))) ?>).
    </div>

<?php endif; ?>

<?php 
include __DIR__ . '/../layout/footer.php'; 
?>