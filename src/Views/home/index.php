<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Principal</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <header>
        <h1>Dashboard Principal</h1>
    </header>
    <main class="dashboard-container">
        <div class="card saldo">
            <h2 class="card-title">Saldo Atual em Caixa</h2>
            <p class="card-value">R$ 1.250,75</p>
        </div>

        <div class="card vendas-hoje">
            <h2 class="card-title">Total Vendido Hoje</h2>
            <p class="card-value">R$ 480,50</p>
        </div>

        <div class="card estoque-baixo">
            <h2 class="card-title">Produtos com Estoque Baixo</h2>
            <p class="card-value">8</p>
            <a href="/produtos/estoque-baixo" class="card-link">Ver produtos</a>
        </div>

        <div class="card ultimos-lancamentos">
            <h2 class="card-title">Últimos Lançamentos</h2>
            <ul class="lancamentos-lista">
                <li>
                    <span class="descricao">Venda #1024</span>
                    <span class="valor entrada">+ R$ 150,00</span>
                </li>
                <li>
                    <span class="descricao">Pagamento Fornecedor XYZ</span>
                    <span class="valor saida">- R$ 300,00</span>
                </li>
                <li>
                    <span class="descricao">Venda #1023</span>
                    <span class="valor entrada">+ R$ 55,90</span>
                </li>
                <li>
                    <span class="descricao">Sangria de Caixa</span>
                    <span class="valor saida">- R$ 100,00</span>
                </li>
                 <li>
                    <span class="descricao">Venda #1022</span>
                    <span class="valor entrada">+ R$ 25,00</span>
                </li>
            </ul>
        </div>
    </main>
</body>
</html>




<!-- Corpo da Home -->

</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>



