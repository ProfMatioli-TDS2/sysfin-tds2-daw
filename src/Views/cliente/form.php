<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h2><?= isset($cliente) ? 'Editar Cliente' : 'Novo Cliente' ?></h2>

    <!-- 
      AÇÃO CORRIGIDA: 
      Verifica se é edição e usa a rota /editar/{id}
      ou se é criação e usa a rota /criar
    -->
    <?php
    if (isset($cliente) && $cliente->id) {
        $actionUrl = BASE_URL . '/index.php?url=/clientes/editar/' . $cliente->id;
    } else {
        $actionUrl = BASE_URL . '/index.php?url=/clientes/criar';
    }
    ?>

    <form method="POST" action="<?php echo $actionUrl; ?>">
        <div class="form-group mb-3">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control"
                   value="<?= htmlspecialchars($cliente->nome ?? '') ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="cpf_cnpj">CPF/CNPJ</label>
            <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="form-control"
                   value="<?= htmlspecialchars($cliente->cpf_cnpj ?? '') ?>" 
                   required
                   maxlength="18"> <!-- Limite para 00.000.000/0000-00 -->
        </div>

        <div class="form-group mb-3">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="<?= htmlspecialchars($cliente->email ?? '') ?>">
        </div>

        <div class="form-group mb-3">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" class="form-control"
                   value="<?= htmlspecialchars($cliente->telefone ?? '') ?>">
        </div>

        <!-- LINK "CANCELAR" CORRIGIDO -->
        <a href="<?php echo BASE_URL; ?>/index.php?url=/clientes" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

<!-- 
  NOVO: Script de Máscara (Requisito da Tarefa)
  Aplica a máscara de CPF/CNPJ
-->
<script>
document.getElementById('cpf_cnpj').addEventListener('input', function (e) {
    var v = e.target.value.replace(/\D/g, ''); // Remove tudo que não é dígito

    if (v.length <= 11) { // CPF
        v = v.replace(/(\d{3})(\d)/, '$1.$2'); // 999.9
        v = v.replace(/(\d{3})(\d)/, '$1.$2'); // 999.999.9
        v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2'); // 999.999.999-99
    } else { // CNPJ
        v = v.slice(0, 14); // Limita em 14 dígitos
        v = v.replace(/^(\d{2})(\d)/, '$1.$2'); // 99.9
        v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3'); // 99.999.9
        v = v.replace(/\.(\d{3})(\d)/, '.$1/$2'); // 99.999.999/9
        v = v.replace(/(\d{4})(\d)/, '$1-$2'); // 99.999.999/9999-99
    }
    e.target.value = v;
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>