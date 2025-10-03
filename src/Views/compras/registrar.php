<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Compras</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <h1>Tela de Registro de Compras</h1>

        <form id="form-compra" action="api/salvar_compra.php" method="POST">

            <div class="form-group">
                <label for="fornecedor">Fornecedor:</label>
                <select id="fornecedor" name="fornecedor_id" required>
                    <option value="">Carregando...</option>
                    </select>
            </div>

            <fieldset>
                <legend>Adicionar Item</legend>
                <div class="item-form">
                    <div class="form-group">
                        <label for="produto">Produto:</label>
                        <select id="produto">
                           <option value="">Carregando...</option>
                           </select>
                    </div>
                    <div class="form-group">
                        <label for="quantidade">Quantidade:</label>
                        <input type="number" id="quantidade" value="1" min="1">
                    </div>
                    <div class="form-group">
                        <label for="valor_unitario">Valor Unitário (R$):</label>
                        <input type="number" id="valor_unitario" step="0.01" min="0">
                    </div>
                    <button type="button" id="btn-adicionar">Adicionar</button>
                </div>
            </fieldset>

            <h2>Itens da Compra</h2>
            <table id="tabela-itens">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Qtd.</th>
                        <th>Vlr. Unitário</th>
                        <th>Subtotal</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    </tbody>
            </table>

            <div class="total-section">
                <h2>VALOR TOTAL DA COMPRA:</h2>
                <span id="valor-total">R$ 0,00</span>
            </div>
            
            <button type="submit" class="btn-finalizar">Finalizar Compra</button>

        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/scripts.js"></script>

</body>
</html>