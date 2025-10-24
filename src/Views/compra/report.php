<?php 

include __DIR__ . '/../layout/header.php'; 


$compras = $compras ?? [];
$totalCompras = $totalCompras ?? 0;
$valorTotal = $valorTotal ?? 0.00;
$dataInicial = $dataInicial ?? '';
$dataFinal = $dataFinal ?? '';
?>

<h2>Relatório de Compras por Período</h2>


<form method="POST" action="">
    <div style="margin-bottom: 20px;">
        <label for="data_inicial" style="display: block; margin-bottom: 5px;">Data Inicial:</label>
        <input type="date" id="data_inicial" name="data_inicial" value="<?= htmlspecialchars($dataInicial) ?>" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>

    <div style="margin-bottom: 20px;">
        <label for="data_final" style="display: block; margin-bottom: 5px;">Data Final:</label>
        <input type="date" id="data_final" name="data_final" value="<?= htmlspecialchars($dataFinal) ?>" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    
    <button type="submit" style="padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
        Gerar Relatório
    </button>
</form>

<hr style="margin-top: 30px; margin-bottom: 30px;">

<?php if (!empty($compras)): ?>
    
    <h3>Resultado para o Período de <?= htmlspecialchars($dataInicial) ?> a <?= htmlspecialchars($dataFinal) ?></h3>
    
    <table border="1" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 10px; text-align: left;">Data</th>
                <th style="padding: 10px; text-align: left;">Fornecedor</th>
                <th style="padding: 10px; text-align: right;">Valor Total da Compra</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($compras as $compra): ?>
                <tr>
                    <td style="padding: 10px;"><?= htmlspecialchars($compra['data_compra']) ?></td>
                    <td style="padding: 10px;"><?= htmlspecialchars($compra['nome_fornecedor']) ?></td>
                    <td style="padding: 10px; text-align: right;">R$ <?= number_format($compra['valor_total'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    
    <div style="padding: 15px; border: 1px solid #ddd; border-radius: 4px; background-color: #f9f9f9;">
        <p style="margin: 0 0 5px 0;">
            <strong>Número Total de Compras:</strong> <?= $totalCompras ?>
        </p>
        <p style="margin: 0;">
            <strong>Valor Total Comprado:</strong> R$ <?= number_format($valorTotal, 2, ',', '.') ?>
        </p>
    </div>
    
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
   
    <p style="color: #dc3545;">Nenhuma compra encontrada no período selecionado de <?= htmlspecialchars($dataInicial) ?> a <?= htmlspecialchars($dataFinal) ?>.</p>
<?php endif; ?>

<?php 

include __DIR__ . '/../layout/footer.php'; 
?>
