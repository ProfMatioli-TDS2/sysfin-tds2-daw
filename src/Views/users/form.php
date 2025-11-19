<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h1 class="my-4">
        <?php echo (isset($usuario) && $usuario->id) ? 'Editar Usuário' : 'Novo Usuário'; ?>
    </h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php
    $actionUrl = (isset($usuario) && $usuario->id)
        ? BASE_URL . '/index.php?url=/users/editar/' . $usuario->id
        : BASE_URL . '/index.php?url=/users/criar';
    
    // O Controller agora passa $usuario preenchido (com POST) em caso de erro
    $nome = $usuario->nome ?? '';
    $login = $usuario->login ?? '';
    $ativo = $usuario->ativo ?? 1; 
    
    // O Controller agora passa $perfisDoUsuario preenchido (com POST) em caso de erro
    $perfisDoUsuario = $perfisDoUsuario ?? []; 
    
    $senhaObrigatoria = (!isset($usuario) || !$usuario->id) ? 'required' : '';
    ?>

    <form method="POST" action="<?php echo $actionUrl; ?>" id="userForm">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="nome" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nome" name="nome"
                       value="<?php echo htmlspecialchars($nome); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="login" class="form-label">Login</label>
                <input type="text" class="form-control" id="login" name="login"
                       value="<?php echo htmlspecialchars($login); ?>" required>
            </div>
            <div class="col-md-12">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" 
                       <?php echo $senhaObrigatoria; ?>>
                <?php if (isset($usuario) && $usuario->id): ?>
                    <div class="form-text">Deixe em branco para não alterar a senha.</div>
                <?php endif; ?>
            </div>

            <div class="col-md-12">
                <label class="form-label">Perfis de Acesso (Selecione pelo menos um)</label>
                <?php foreach ($perfisDisponiveis as $perfil): ?>
                    <?php
                    // --- INÍCIO DA CORREÇÃO ---
                    // REMOVIDA A LEITURA DO $_POST.
                    // A View agora confia 100% na variável $perfisDoUsuario vinda do Controller.
                    $checked = in_array($perfil->id, $perfisDoUsuario) ? 'checked' : '';
                    // --- FIM DA CORREÇÃO ---
                    ?>
                    <div class="form-check">
                        <input class="form-check-input check-perfil" type="checkbox" name="perfis[]" 
                               value="<?php echo $perfil->id; ?>" 
                               id="perfil-<?php echo $perfil->id; ?>"
                               <?php echo $checked; ?>>
                        <label class="form-check-label" for="perfil-<?php echo $perfil->id; ?>">
                            <?php echo htmlspecialchars($perfil->nome); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <div id="perfil-error" class="text-danger d-none mt-2">
                    Erro: Você deve selecionar pelo menos um perfil.
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="ativo" 
                           name="ativo" value="1" <?php if ($ativo) echo 'checked'; ?>>
                    <label class="form-check-label" for="ativo">Usuário Ativo</label>
                </div>
            </div>
        </div>

        <a href="<?php echo BASE_URL; ?>/index.php?url=/users" class="btn btn-secondary mt-4">Cancelar</a>
        <button type="submit" class="btn btn-primary mt-4">Salvar</button>
    </form>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<script>
document.getElementById('userForm').addEventListener('submit', function(event) {
    const checkboxes = document.querySelectorAll('.check-perfil');
    let isChecked = false;
    
    for (const checkbox of checkboxes) {
        if (checkbox.checked) {
            isChecked = true;
            break;
        }
    }

    if (!isChecked) {
        event.preventDefault(); // Para o envio
        document.getElementById('perfil-error').classList.remove('d-none'); // Mostra o erro
    } else {
        document.getElementById('perfil-error').classList.add('d-none'); // Esconde o erro
    }
});
</script>