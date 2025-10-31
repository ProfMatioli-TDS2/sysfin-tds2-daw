<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h1>Tela de Registro de Compras</h1>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
        <div class="error">
            Erro ao processar: <?= htmlspecialchars($_GET['msg'] ?? 'Tente novamente') ?>
        </div>
    <?php endif; ?>

    <form action="/compras/registrar" method="POST">
        
        <div class="form-group">
            <label for="fornecedor">Fornecedor:</label>
            <select id="fornecedor" name="fornecedor_id" required>
                <option value="">Selecione um fornecedor</option>
                <?php foreach ($fornecedores ?? [] as $fornecedor): ?>
                    <option value="<?= htmlspecialchars($fornecedor->id) ?>" 
                        <?= ($selected_fornecedor_id ?? null) == $fornecedor->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($fornecedor->nome) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <fieldset>
            <legend>Adicionar Item</legend>
            <label>Produto: 
                <select name="produto_id">
                    <option value="">Selecione</option>
                    <?php foreach ($produtos ?? [] as $produto): ?>
                        <option value="<?= htmlspecialchars($produto->id) ?>">
                            <?= htmlspecialchars($produto->nome) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Quantidade: <input type="number" name="quantidade" value="1" min="1"></label>
            <label>Valor Unitário (R$): <input type="text" name="valor_unitario" placeholder="10,50"></label>
            
            <button type="submit" name="action" value="add_item">Adicionar</button>
        </fieldset>

        <h2>Itens da Compra</h2>
        <table border="1" style="width:100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr><th>Produto</th><th>Qtd.</th><th>Vlr. Unitário</th><th>Subtotal</th><th>Ação</th></tr>
            </thead>
            <tbody>
                <?php if (empty($itens_compra)): ?>
                    <tr><td colspan="5" style="text-align:center;">Nenhum item adicionado.</td></tr>
                <?php else: ?>
                    <?php foreach ($itens_compra as $index => $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td><?= htmlspecialchars($item['qtd']) ?></td>
                            <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                            <td><a href="/compras/registrar?action=remove_item&index=<?= $index ?>">Remover</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="total-section" style="margin-top: 20px; text-align: right;">
            <h2>VALOR TOTAL DA COMPRA:</h2>
            <span style="font-size: 1.5em; font-weight: bold;">
                R$ <?= number_format($total_compra ?? 0, 2, ',', '.') ?>
            </span>
        </div>
        
        <button type="submit" name="action" value="finalize" class="btn-finalizar" style="margin-top: 20px;">Finalizar Compra</button>
        <a href="/compras/registrar?action=clear" style="margin-left: 10px;">Cancelar Compra</a>
    </form>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>