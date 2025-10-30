<?php require __DIR__ . '/../layout/header.php'; ?>
<!-- Esta linha busca o CSS na pasta 'public/css/dashboard.css' -->
<link rel="stylesheet" href="public/css/dashboard.css">

<h1 class="mb-4">Dashboard Principal</h1>

<main class="dashboard-container">
    
    <div class="card saldo">
        <h3>Saldo do Caixa</h3>
        <p>R$ <?= number_format($saldoatual, 2, ',', '.') ?></p>
    </div>
    
    <div class="card vendido-hoje">
        <h3>Total Vendido Hoje</h3>
        <p>R$ <?= number_format($totalVendidoHoje, 2, ',', '.') ?></p>
    </div>
    
    <div class="card estoque-baixo">
        <h3>Produtos com Estoque Baixo</h3>
        <p><?= $estoqueBaixo ?></p>
    </div>

    <div class="card lancamentos">
        <h3>Últimos Lançamentos</h3>
        <ul>
            <?php foreach ($ultimosLancamentos as $lancamento): ?>
                <li class="<?= $lancamento['tipo'] == 'S' ? 'saida' : 'entrada' ?>">
                    <?= htmlspecialchars($lancamento['descricao']) ?>
                    
                    <span>
                        <?= ($lancamento['tipo'] == 'S' ? '-' : '+') ?>
                        R$ <?= number_format($lancamento['valor'], 2, ',', '.') ?>
                    </span>
                    
                    <small>
                        (<?= date('d/m/Y H:i', strtotime($lancamento['data_movimento'])) ?>)
                    </small>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
