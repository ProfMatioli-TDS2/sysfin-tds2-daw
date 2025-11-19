<?php 
// O Controller já fez o 'extract($data)', 
// então $fornecedores e $produtos estão disponíveis.
require __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <h1 class="my-4">Registrar Nova Compra</h1>

    <!-- Exibe erros do Controller (ex: falha ao salvar) -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <strong>Erro ao finalizar:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <!-- 1. Formulário Principal (só envia Fornecedor e os Itens/Total escondidos) -->
    <form id="formCompra" method="POST" action="<?php echo BASE_URL; ?>/index.php?url=/compras/registrar">
        
        <div class="mb-3">
            <label for="id_fornecedor" class="form-label fs-5">Fornecedor</label>
            <select id="id_fornecedor" name="id_fornecedor" class="form-select form-select-lg" required>
                <option value="">Selecione um fornecedor</option>
                <?php foreach ($fornecedores as $fornecedor): ?>
                    <option value="<?php echo $fornecedor->id; ?>">
                        <?php echo htmlspecialchars($fornecedor->nome); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Inputs escondidos para os itens e total serão adicionados aqui via JS -->
    </form>
    
    <hr class="my-4">

    <!-- 2. Seção de Adicionar Itens (NÃO faz parte do form principal) -->
    <fieldset class="card bg-light p-3">
        <legend class="fs-5">Adicionar Itens à Compra</legend>
        
        <div class="row g-3">
            <div class="col-md-5">
                <label for="itemProduto" class="form-label">Produto</label>
                <select id="itemProduto" class="form-select">
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtos as $produto): ?>
                        <!-- 
                            CORREÇÃO AQUI: Adicionado 'data-preco'
                            (Estamos a usar o 'preco_venda' como sugestão de custo)
                        -->
                        <option value="<?php echo $produto->id; ?>" 
                                data-nome="<?php echo htmlspecialchars($produto->nome); ?>"
                                data-preco="<?php echo $produto->preco_venda; ?>">
                            <?php echo htmlspecialchars($produto->nome); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="itemQtd" class="form-label">Quantidade</label>
                <input type="number" id="itemQtd" class="form-control" value="1" min="1">
            </div>

            <div class="col-md-3">
                <label for="itemValor" class="form-label">Valor Unit. (Custo) R$</label>
                <input type="text" id="itemValor" class="form-control" placeholder="10,50">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <!-- Botão "Adicionar" (Requisito 2) -->
                <button type="button" id="btnAdicionar" class="btn btn-primary w-100">Adicionar</button>
            </div>
        </div>
        <div id="addItemError" class="text-danger mt-2 d-none"></div>
    </fieldset>
    
    <hr class="my-4">

    <!-- 3. Grid Temporário (Requisito 3) -->
    <h2 class="h4">Itens da Compra</h2>
    <table class="table table-striped table-bordered">
        <thead class="table-light">
            <tr>
                <th>Produto</th>
                <th class="text-center">Qtd.</th>
                <th class="text-end">Valor Unitário</th>
                <th class="text-end">Subtotal</th>
                <th class="text-center" style="width: 100px;">Ação</th>
            </tr>
        </thead>
        <tbody id="tabelaItens">
            <!-- Itens serão adicionados aqui via JS -->
            <tr id="placeholderItens">
                <td colspan="5" class="text-center text-muted">Nenhum item adicionado.</td>
            </tr>
        </tbody>
    </table>

    <!-- 4. Total em Tempo Real (Requisito 4) -->
    <div class="d-flex justify-content-end align-items-center">
        <h2 class="h3 mb-0 me-3">Valor Total:</h2>
        <h2 class="h2 mb-0 fw-bold text-success">
            R$ <span id="valorTotalCompra">0,00</span>
        </h2>
    </div>

    <hr class="my-4">

    <!-- 5. Botão Finalizar (Requisito 5) -->
    <div class="d-grid gap-2">
        <button type="button" id="btnFinalizar" class="btn btn-success btn-lg">
            Finalizar Compra
        </button>
    </div>

</div>

<?php require __DIR__ . '/../layout/footer.php'; ?>

<!-- Scripts (jQuery e Mask - já usados em outros CRUDS) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
$(document).ready(function(){
    
    // Aplica máscara no campo de valor (para R$)
    $('#itemValor').mask('000.000,00', { reverse: true });

    // Armazena os itens da compra num array JS
    let itensCompra = [];
    let itemCounter = 0; // Para garantir IDs únicos de remoção

    // =====================================================================
    // NOVO: Preencher Preço ao Mudar Produto
    // =====================================================================
    $('#itemProduto').on('change', function() {
        const preco = $(this).find('option:selected').data('preco');
        
        if (preco && preco > 0) {
            // Converte o número (ex: 12.5) para o formato da máscara (ex: "12,50")
            const precoFormatado = parseFloat(preco).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).replace('.', ''); // Remove o separador de milhar se houver
            
            // Define o valor no campo
            $('#itemValor').val(precoFormatado);
            
            // Força o plugin de máscara a reformatar (essencial)
            $('#itemValor').trigger('input');

            // Foca no campo de quantidade para o próximo passo
            $('#itemQtd').focus().select();
        } else {
            // Se o produto não tiver preço, limpa o campo
            $('#itemValor').val('');
        }
    });

    /**
     * Requisito 2 e 3: Adicionar item ao grid temporário
     */
    $('#btnAdicionar').on('click', function() {
        // Limpa erro anterior
        $('#addItemError').addClass('d-none').text('');

        // 1. Coletar dados dos inputs
        const produtoSelect = $('#itemProduto');
        const id_produto = produtoSelect.val();
        const nome_produto = produtoSelect.find('option:selected').data('nome');
        
        // Converte valor (ex: "10,50") para número (ex: 10.50)
        let valor_unitario_str = $('#itemValor').val().replace(/\./g, '').replace(',', '.');
        const valor_unitario = parseFloat(valor_unitario_str);
        
        const quantidade = parseInt($('#itemQtd').val());

        // 2. Validar
        if (!id_produto || valor_unitario <= 0 || !valor_unitario || quantidade <= 0 || !quantidade) {
            $('#addItemError').removeClass('d-none').text('Por favor, preencha Produto, Quantidade e Valor Unitário válidos.');
            return;
        }

        const subtotal = quantidade * valor_unitario;

        // 3. Adicionar ao array
        itensCompra.push({
            id_array: itemCounter++, // ID único para remoção
            id_produto: id_produto,
            nome: nome_produto,
            quantidade: quantidade,
            valor_unitario: valor_unitario,
            subtotal: subtotal
        });

        // 4. Atualizar a tela
        atualizarGridEVisualizacao();

        // 5. Limpar inputs
        $('#itemProduto').val('');
        $('#itemQtd').val('1');
        $('#itemValor').val('');
        $('#itemProduto').focus(); // Foca no produto para o próximo item
    });

    /**
     * Remove um item do grid (clicando em "Remover")
     */
    $('#tabelaItens').on('click', '.btn-remover-item', function() {
        const idParaRemover = $(this).data('id-array');
        
        // Filtra o array, mantendo todos, exceto o item a ser removido
        itensCompra = itensCompra.filter(item => item.id_array !== idParaRemover);
        
        atualizarGridEVisualizacao();
    });

    /**
     * Função para atualizar o grid (tabela) e o valor total
     */
    function atualizarGridEVisualizacao() {
        const tabelaBody = $('#tabelaItens');
        tabelaBody.empty(); // Limpa a tabela
        
        if (itensCompra.length === 0) {
            tabelaBody.append('<tr id="placeholderItens"><td colspan="5" class="text-center text-muted">Nenhum item adicionado.</td></tr>');
        }

        let valorTotalCalculado = 0;

        itensCompra.forEach(item => {
            valorTotalCalculado += item.subtotal;
            
            const linha = `
                <tr>
                    <td>${item.nome}</td>
                    <td class="text-center">${item.quantidade}</td>
                    <td class="text-end">R$ ${formatarMoeda(item.valor_unitario)}</td>
                    <td class="text-end">R$ ${formatarMoeda(item.subtotal)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btn-remover-item" data-id-array="${item.id_array}">
                            Remover
                        </button>
                    </td>
                </tr>
            `;
            tabelaBody.append(linha);
        });

        // Requisito 4: Atualizar Total em Tempo Real
        $('#valorTotalCompra').text(formatarMoeda(valorTotalCalculado));
    }

    /**
     * Requisito 5: Finalizar a Compra
     */
    $('#btnFinalizar').on('click', function() {
        // Validações
        const idFornecedor = $('#id_fornecedor').val();
        if (!idFornecedor) {
            alert('Erro: Por favor, selecione um fornecedor.');
            return;
        }
        if (itensCompra.length === 0) {
            alert('Erro: Adicione pelo menos um item à compra.');
            return;
        }

        // Limpa inputs escondidos antigos
        $('#formCompra input[type="hidden"]').remove();

        let valorTotalFinal = 0;

        // Adiciona os itens como inputs escondidos ao formulário principal
        itensCompra.forEach((item, index) => {
            $('#formCompra').append(`<input type="hidden" name="itens[${index}][id_produto]" value="${item.id_produto}">`);
            $('#formCompra').append(`<input type="hidden" name="itens[${index}][quantidade]" value="${item.quantidade}">`);
            $('#formCompra').append(`<input type="hidden" name="itens[${index}][valor_unitario]" value="${item.valor_unitario}">`);
            
            valorTotalFinal += item.subtotal;
        });

        // Adiciona o valor total final
        $('#formCompra').append(`<input type="hidden" name="valor_total" value="${valorTotalFinal}">`);

        // Envia o formulário principal
        $('#formCompra').submit();
    });

    /**
     * Helper para formatar R$
     */
    function formatarMoeda(valor) {
        return valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

});
</script>