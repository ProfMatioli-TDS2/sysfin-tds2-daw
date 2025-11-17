<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SysFin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/estilo.css"> 
    
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5; /* Um fundo cinza claro */
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border: 0;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
    </style>
</head>
<body class="text-center">

    <main class="form-signin">
        <div class="card login-card">
            <h1 class="h3 mb-3 fw-normal">SysFin - Login</h1>
    
            <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
    
            <form method="POST" action="<?php echo BASE_URL; ?>/index.php?url=/auth">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="login" name="login" placeholder="seu-login" required autofocus>
                    <label for="login">Login (usuário)</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
                    <label for="senha">Senha</label>
                </div>
    
                <button class="w-100 btn btn-lg btn-primary" type="submit">Entrar</button>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>