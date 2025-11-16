<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">Editar Fornecedor</h2>

    <?php if (!empty($erros)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($erros as $mensagem): ?>
                    <li><?= htmlspecialchars($mensagem) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- CORRIGIDO: action do formulário -->
    <form method="POST" action="<?php echo BASE_URL; ?>/index.php?url=/fornecedores/editar/<?= $fornecedor->id ?>" class="row g-3">
        <div class="col-md-6">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" name="nome" id="nome"
                   class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
                   value="<?= htmlspecialchars($_POST['nome'] ?? $fornecedor->nome) ?>" required>
        </div>

        <div class="col-md-6">
            <label for="cnpj" class="form-label">CNPJ:</label>
            <input type="text" name="cnpj" id="cnpj"
                   class="form-control <?= isset($erros['cnpj']) ? 'is-invalid' : '' ?>"
                   value="<?= htmlspecialchars($_POST['cnpj'] ?? $fornecedor->cnpj) ?>" required>
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" id="email"
                   class="form-control <?= isset($erros['email']) ? 'is-invalid' : '' ?>"
                   value="<?= htmlspecialchars($_POST['email'] ?? $fornecedor->email) ?>">
        </div>

        <div class="col-md-6">
            <label for="telefone" class="form-label">Telefone:</label>
            <input type="text" name="telefone" id="telefone"
                   class="form-control <?= isset($erros['telefone']) ? 'is-invalid' : '' ?>"
                   value="<?= htmlspecialchars($_POST['telefone'] ?? $fornecedor->telefone) ?>">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="<?php echo BASE_URL; ?>/index.php?url=/fornecedores" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function(){
        $('#cnpj').mask('00.000.000/0000-00');
        $('#telefone').mask('(00) 00000-0000');
    });
</script>