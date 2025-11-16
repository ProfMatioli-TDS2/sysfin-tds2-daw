<?php require __DIR__ . '/../layout/header.php'; ?>

<div class="container">
    <h1 class="my-4">
        <?php echo isset($planoConta) ? 'Editar Plano de Conta' : 'Novo Plano de Conta'; ?>
    </h1>

    <?php
        // Define a ação do formulário (Criar ou Editar)
        $actionUrl = isset($planoConta)
            ? BASE_URL . '/index.php?url=/plano-contas/editar/' . $planoConta->id
            : BASE_URL . '/index.php?url=/plano-contas/criar';
        
        $descricao = $planoConta->descricao ?? '';
        $tipo = $planoConta->tipo ?? 'D'; // Padrão 'Despesa'
        
        // Verifica se é uma conta padrão (IDs 1-5). Se for, o 'Tipo' não pode ser alterado.
        $isProtegido = (isset($planoConta) && $planoConta->id <= 5);
    ?>

    <form method="POST" action="<?php echo $actionUrl; ?>">
        
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control" id="descricao" name="descricao" 
                   value="<?php echo htmlspecialchars($descricao); ?>" required>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select class="form-select" id="tipo" name="tipo" <?php if ($isProtegido) echo 'disabled'; ?>>
                <!-- 
                  Valores são 'R' e 'D' para bater com o banco (CHAR 1)
                -->
                <option value="D" <?php if ($tipo === 'D') echo 'selected'; ?>>Despesa</option>
                <option value="R" <?php if ($tipo === 'R') echo 'selected'; ?>>Receita</option>
            </select>
            
            <?php if ($isProtegido): ?>
                <div class="form-text text-muted">
                    O tipo de contas padrão (Receita/Despesa) não pode ser alterado.
                </div>
                <!-- Passa o tipo original caso esteja desabilitado -->
                <input type="hidden" name="tipo" value="<?php echo htmlspecialchars($tipo); ?>" />
            <?php endif; ?>
        </div>

        <a href="<?php echo BASE_URL; ?>/index.php?url=/plano-contas" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>