<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Novo Usuário</title>
    <style>
        body { font-family: sans-serif; display: grid; place-items: center; min-height: 90vh; background-color: #f4f4f4; }
        .register-container { background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 25px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); width: 400px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] { width: 95%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%; }
        .btn:hover { background-color: #218838; }
        .error { color: red; background: #ffebee; border: 1px solid red; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .perfis-group { border: 1px solid #ddd; padding: 10px; border-radius: 4px; }
        .perfis-group label { display: block; margin-bottom: 5px; }
        .login-link { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
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
</body>
</html>
