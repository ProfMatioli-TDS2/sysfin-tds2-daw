<?php 
require __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <h1 class="my-4">Lançamento Manual no Caixa</h1>

    <!-- Exibe mensagens de Sucesso ou Erro -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo BASE_URL; ?>/index.php?url=/lancamento-manual-caixa">
        <div class="row g-3">
            
            <!-- Data do Lançamento -->
            <div class="col-md-4">
                <label for="data_movimento" class="form-label">Data do Lançamento</label>
                <input type="date" class="form-control" id="data_movimento" name="data_movimento"
                       value="<?php echo $_POST['data_movimento'] ?? date('Y-m-d'); ?>" required>
            </div>
            
            <!-- Tipo (Entrada/Saída) -->
            <div class="col-md-4">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-select" id="tipo" name="tipo" required>
                    <option value="">Selecione...</option>
                    <option value="S" <?php echo (($_POST['tipo'] ?? '') === 'S') ? 'selected' : ''; ?>>Saída (Despesa)</option>
                    <option value="E" <?php echo (($_POST['tipo'] ?? '') === 'E') ? 'selected' : ''; ?>>Entrada (Receita)</option>
                </select>
            </div>
            
            <!-- Valor -->
            <div class="col-md-4">
                <label for="valor" class="form-label">Valor (R$)</label>
                <input type="text" class="form-control" id="valor" name="valor" 
                       value="<?php echo htmlspecialchars($_POST['valor'] ?? ''); ?>"
                       placeholder="0,00" required>
            </div>

            <!-- Plano de Contas (Filtrado por JS) -->
            <div class="col-md-8">
                <label for="id_plano_de_contas" class="form-label">Plano de Contas (Categoria)</label>
                <select class="form-select" id="id_plano_de_contas" name="id_plano_de_contas" required>
                    <option value="">Selecione o Tipo (Entrada/Saída) primeiro</option>
                    <!-- Opções serão preenchidas pelo JavaScript -->
                </select>
            </div>
            
            <!-- Descrição -->
            <div class="col-md-12">
                <label for="descricao" class="form-label">Descrição</label>
                <input type="text" class="form-control" id="descricao" name="descricao"
                       value="<?php echo htmlspecialchars($_POST['descricao'] ?? ''); ?>"
                       placeholder="Ex: Pagamento de conta de luz" required>
            </div>

        </div>

        <button type="submit" class="btn btn-primary mt-4">Salvar Lançamento</button>
    </form>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<!-- Scripts (jQuery e Mask) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
$(document).ready(function(){
    
    // Aplica a máscara de dinheiro
    $('#valor').mask('000.000.000,00', { reverse: true });

    // --- Lógica para filtrar o Plano de Contas ---

    // 1. Armazena todas as contas do PHP em um array JavaScript
    const planosConta = [
        <?php foreach ($planosConta as $plano): ?>
        {
            id: <?php echo $plano->id; ?>,
            
            // ==========================================================
            // ERRO CORRIGIDO AQUI
            // Usando json_encode para garantir que plicas (d'água)
            // não quebrem a string JavaScript.
            // ==========================================================
            descricao: <?php echo json_encode($plano->descricao); ?>,
            
            // Converte 'R' para 'E' (Entrada) e 'D' para 'S' (Saída)
            tipo: "<?php echo ($plano->tipo === 'R') ? 'E' : 'S'; ?>" 
        },
        <?php endforeach; ?>
    ];
    
    const selectTipo = $('#tipo');
    const selectPlanoConta = $('#id_plano_de_contas');
    
    // 2. Função para atualizar o dropdown
    function atualizarPlanoDeContas() {
        const tipoSelecionado = selectTipo.val(); // 'E' ou 'S'
        selectPlanoConta.empty(); // Limpa as opções atuais
        
        if (!tipoSelecionado) {
            selectPlanoConta.append('<option value="">Selecione o Tipo (Entrada/Saída) primeiro</option>');
            return;
        }

        // Filtra o array JS
        const contasFiltradas = planosConta.filter(plano => plano.tipo === tipoSelecionado);
        
        if (contasFiltradas.length === 0) {
             selectPlanoConta.append('<option value="">Nenhuma conta encontrada para este tipo</option>');
             return;
        }
        
        selectPlanoConta.append('<option value="">Selecione a categoria...</option>');

        // 3. Adiciona as opções filtradas
        contasFiltradas.forEach(plano => {
            selectPlanoConta.append(
                $('<option></option>')
                    .attr('value', plano.id)
                    .text(plano.descricao)
            );
        });
    }

    // 4. Gatilho: Quando o 'Tipo' muda, chama a função
    selectTipo.on('change', atualizarPlanoDeContas);
    
    // 5. Chama a função uma vez no carregamento (caso o formulário recarregue com erro)
    atualizarPlanoDeContas();
    
    // (Bónus: Mantém o item selecionado se o formulário recarregar com erro)
    <?php if (isset($_POST['id_plano_de_contas'])): ?>
        // Garante que a opção correta seja selecionada após o JS carregar as opções
        selectPlanoConta.val("<?php echo (int)$_POST['id_plano_de_contas']; ?>");
    <?php endif; ?>

});
</script>