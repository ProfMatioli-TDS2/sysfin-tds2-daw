<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="register-container">
    <h2>Registrar Novo Usuário</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">
            <?php 
                switch ($_GET['error']) {
                    case 'validacao':
                        echo 'Por favor, preencha todos os campos e verifique sua senha.';
                        break;
                    case 'email_duplicado':
                        echo 'Este email já está cadastrado. Tente outro.';
                        break;
                    default:
                        echo 'Ocorreu um erro. Tente novamente.';
                }
            ?>
        </div>
    <?php endif; ?>

    <form action="/usuarios/criar" method="POST" id="registerForm">
        <div class="form-group">
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <div class="form-group">
            <label for="senha_confirmacao">Confirmar Senha:</label>
            <input type="password" id="senha_confirmacao" name="senha_confirmacao" required>
            <small id="senhaError" style="color:red; display:none;">As senhas não conferem.</small>
        </div>

        <div class="form-group perfis-group">
            <label>Perfis de Usuário (selecione ao menos 1):</label>
            <?php foreach ($perfis ?? [] as $perfil): ?>
                <div>
                    <input type="checkbox" name="perfis[]" id="perfil_<?= $perfil->id ?>" value="<?= $perfil->id ?>">
                    <label for="perfil_<?= $perfil->id ?>"><?= htmlspecialchars($perfil->nome) ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="btn">Registrar</button>
    </form>
    <div class="login-link">
        <a href="/login">Já tem conta? Faça o login</a>
    </div>
</div>

<script>
    const form = document.getElementById('registerForm');
    const senha = document.getElementById('senha');
    const senhaConf = document.getElementById('senha_confirmacao');
    const senhaError = document.getElementById('senhaError');

    form.addEventListener('submit', function(e) {
        if (senha.value !== senhaConf.value) {
            e.preventDefault(); 
            senhaError.style.display = 'block';
            senhaConf.focus();
        } else {
            senhaError.style.display = 'none';
        }
    });
</script>

<?php require __DIR__ . '/../layout/footer.php'; ?>