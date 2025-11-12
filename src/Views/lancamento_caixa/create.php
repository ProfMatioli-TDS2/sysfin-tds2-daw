<h1>Novo Lançamento Manual</h1>

<form method="POST" action="/lancamentos/novo">
    <label>Data do Lançamento:</label><br>
    <input type="datetime-local" name="data_movimento" required><br><br>

    <label>Descrição:</label><br>
    <input type="text" name="descricao" required><br><br>

    <label>Valor (R$):</label><br>
    <input type="number" name="valor" step="0.01" required><br><br>

    <label>Tipo:</label><br>
    <select name="tipo" required>
        <option value="E">Entrada</option>
        <option value="S">Saída</option>
    </select><br><br>

    <label>Categoria (Plano de Contas):</label><br>
    <select name="id_plano_de_contas" required>
        <?php foreach ($categorias as $c): ?>
            <option value="<?= $c['id'] ?>">
                <?= htmlspecialchars($c['descricao']) ?> (<?= $c['tipo'] ?>)
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">💾 Salvar</button>
</form>

<br>
<a href="/lancamentos">← Voltar</a>
