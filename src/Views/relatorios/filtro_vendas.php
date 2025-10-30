<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Vendas por Período</title>
</head>
<body>
    <h1>Relatório de Vendas por Período</h1>
    <form method="POST" action="/relatorio/vendas">
        <label>Data Inicial: <input type="date" name="data_inicial" required></label><br><br>
        <label>Data Final: <input type="date" name="data_final" required></label><br><br>
        <button type="submit">Gerar Relatório</button>
    </form>
</body>
</html>
