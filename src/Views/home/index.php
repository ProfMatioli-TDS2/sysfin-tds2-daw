<?php require __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="public/css/dashboard.css">

<h1 class="mb-4">Dashboard Principal</h1>

<main class="dashboard-container">
    
    <?php if (\App\Core\SessionManager::hasRole('Administrador') || \App\Core\SessionManager::hasRole('Tesoureiro')): ?>
        <div class="card saldo">
            <h3>Saldo do Caixa</h3>
            <p>R$ <?= number_format($saldoatual, 2, ',', '.') ?></p>
        </div>
    <?php endif; ?>
    
    <div class="card vendido-hoje">
        <h3>Total Vendido Hoje</h3>
        <p>R$ <?= number_format($totalVendidoHoje, 2, ',', '.') ?></p>
    </div>
    
    <div class="card estoque-baixo">
        <h3>Produtos com Estoque Baixo</h3>
        <p><?= $estoqueBaixo ?></p>
    </div>

    <?php if (\App\Core\SessionManager::hasRole('Administrador') || \App\Core\SessionManager::hasRole('Tesoureiro')): ?>
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
    <?php endif; ?>

</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>