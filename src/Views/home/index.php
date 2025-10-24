<?php require __DIR__ . '/../layout/header.php'; ?>


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


<style>
    .dashboard-container {
        display: flex;
        flex-wrap: wrap; 
        gap: 20px; 
        justify-content: flex-start;
    }
    
    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        background-color: #fff;
        flex-basis: 300px; 
        flex-grow: 1; 
    }

    .card h3 {
        margin-top: 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .card p {
        font-size: 2.2rem;
        font-weight: bold;
        color: #333;
        margin: 10px 0 0 0;
    }
    
    
    .card.saldo p {
        color: #007bff; 
    }
    
    .card.vendido-hoje p {
        color: #28a745; 
    }
    
    .card.estoque-baixo p {
        color: #dc3545; 
        font-size: 2.2rem; 
    }

    
    .card.lancamentos ul {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }
    .card.lancamentos li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        flex-wrap: wrap;
    }
    .card.lancamentos li:last-child {
        border-bottom: none;
    }
    .card.lancamentos li span {
        font-weight: bold;
    }
    .card.lancamentos li small {
        color: #777;
        width: 100%; 
        text-align: right;
        font-size: 0.8rem;
    }
    
    /* Cores para entrada e saída */
    .card.lancamentos li.entrada span {
        color: #28a745; /* Verde */
    }
    .card.lancamentos li.saida span {
        color: #dc3545; /* Vermelho */
    }

</style>

<?php require __DIR__ . '/../layout/footer.php'; ?>
