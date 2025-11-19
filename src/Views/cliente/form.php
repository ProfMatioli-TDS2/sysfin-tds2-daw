<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    
    <?php
    // O Controller (create/edit) agora envia 'cliente', 'erros', e 'dados'.
    $cliente = $cliente ?? null;
    $erros = $erros ?? [];
    $dados = $dados ?? []; // $dados vem do $_POST em caso de erro

    // Define o título da página
    $titulo = (isset($cliente) && $cliente->id) ? 'Editar Cliente' : 'Novo Cliente';
    
    // Define a URL da action
    if (isset($cliente) && $cliente->id) {
        $actionUrl = BASE_URL . '/index.php?url=/clientes/editar/' . $cliente->id;
    } else {
        $actionUrl = BASE_URL . '/index.php?url=/clientes/criar';
    }
    ?>

    <h2><?= $titulo ?></h2>

    <?php if (!empty($erros)): ?>
        <div class="alert alert-danger" role="alert">
            <p class="mb-1">Por favor, corrija os seguintes erros:</p>
            <ul class="mb-0">
                <?php foreach ($erros as $erro): ?>
                    <li><?php echo htmlspecialchars($erro); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>


    <form method="POST" action="<?php echo $actionUrl; ?>">
        <div class="form-group mb-3">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control"
                   value="<?= htmlspecialchars($dados['nome'] ?? $cliente->nome ?? '') ?>" required>
        </div>

        <div class="form-group mb-3">
            <label for="cpf_cnpj">CPF/CNPJ</label>
            <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="form-control"
                   value="<?= htmlspecialchars($dados['cpf_cnpj'] ?? $cliente->cpf_cnpj ?? '') ?>" 
                   required
                   maxlength="18"> </div>

        <div class="form-group mb-3">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="<?= htmlspecialchars($dados['email'] ?? $cliente->email ?? '') ?>">
        </div>

        <div class="form-group mb-3">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone" class="form-control"
                   value="<?= htmlspecialchars($dados['telefone'] ?? $cliente->telefone ?? '') ?>"
                   required
                   maxlength="15"> </div>

        <a href="<?php echo BASE_URL; ?>/index.php?url=/clientes" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

<script>
// Máscara de CPF/CNPJ (a que você forneceu)
document.getElementById('cpf_cnpj').addEventListener('input', function (e) {
    var v = e.target.value.replace(/\D/g, ''); 
    if (v.length <= 11) { // CPF
        v = v.replace(/(\d{3})(\d)/, '$1.$2'); 
        v = v.replace(/(\d{3})(\d)/, '$1.$2'); 
        v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    } else { // CNPJ
        v = v.slice(0, 14); 
        v = v.replace(/^(\d{2})(\d)/, '$1.$2'); 
        v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3'); 
        v = v.replace(/\.(\d{3})(\d)/, '.$1/$2'); 
        v = v.replace(/(\d{4})(\d)/, '$1-$2'); 
    }
    e.target.value = v;
});

// ADICIONADO: Máscara de Telefone (8 ou 9 dígitos)
document.getElementById('telefone').addEventListener('input', function (e) {
    var v = e.target.value.replace(/\D/g, ''); 
    v = v.replace(/^(\d{2})(\d)/, '($1) $2'); // Coloca (XX) 
    if (v.length <= 13) { 
        // Fixo (XX) XXXX-XXXX
        v = v.replace(/(\d{4})(\d)/, '$1-$2');
    } else { 
        // Celular (XX) XXXXX-XXXX
        v = v.replace(/(\d{5})(\d)/, '$1-$2');
    }
    e.target.value = v.slice(0, 15); // Limita o tamanho máximo
});
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>