<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Vendas por Período</title>
</head>
<body>
    <h1>Gerar Relatório de Vendas</h1>
    <form method="POST" action="/relatorio-vendas/report">
        <label for="data_inicio">Data Início:</label>
        <input type="date" name="data_inicio" id="data_inicio" required>
        <br><br>
        <label for="data_fim">Data Fim:</label>
        <input type="date" name="data_fim" id="data_fim" required>
        <br><br>
        <button type="submit">Gerar Relatório</button>
    </form>
</body>
</html>
