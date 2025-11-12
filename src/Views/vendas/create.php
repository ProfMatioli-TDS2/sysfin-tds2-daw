<?php 
require __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <h1 class="my-4">Registro de Venda</h1>

    <!-- CORRIGIDO: Action do formulário -->
    <form id="venda-form" method="POST" action="<?php echo BASE_URL; ?>/index.php?url=/vendas/create">

        <div class="mb-3">
            <label for="select-cliente" class="form-label fs-5">Cliente:</label>
            <select id="select-cliente" name="id_cliente" class="form-select form-select-lg" required>
                <option value="">Selecione um cliente...</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= htmlspecialchars($cliente->id) ?>">
                        <?= htmlspecialchars($cliente->nome) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <hr>

        <div class="card mb-3">
            <div class="card-body bg-light">
                <h3 class="card-title">Adicionar Item</h3>
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="select-produto" class="form-label">Produto:</label>
                        <select id="select-produto" class="form-select">
                            <option value="">Selecione um produto...</option>
                            <?php foreach ($produtos as $produto): ?>
                                <!-- Melhoria: Mostra o estoque no nome -->
                                <option value="<?= htmlspecialchars($produto->id) ?>" 
                                        data-preco="<?= htmlspecialchars($produto->preco_venda) ?>" 
                                        data-estoque="<?= htmlspecialchars($produto->estoque) ?>">
                                    <?= htmlspecialchars($produto->nome) ?> (Estoque: <?= htmlspecialchars($produto->estoque) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="valor-unitario" class="form-label">Valor Unitário (R$):</label>
                        <!-- (Requisito: Editável para descontos) -->
                        <input type="text" id="valor-unitario" class="form-control" placeholder="0,00">
                    </div>
                    <div class="col-md-2">
                        <label for="quantidade" class="form-label">Quantidade:</label>
                        <input type="number" id="quantidade" class="form-control" min="1" value="1">
                        <small id="estoque-aviso" class="text-danger d-block mt-1"></small>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" id="btn-adicionar" class="btn btn-primary w-100">Adicionar</button>
                    </div>
                </div>
            </div>
        </div>


        <h3>Itens da Venda</h3>
        <table id="itens-venda-tabela" class="table table-striped table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Produto</th>
                    <th class="text-center">Qtd.</th>
                    <th class="text-end">Vlr. Unitário</th>
                    <th class="text-end">Vlr. Total</th>
                    <th class="text-center">Ação</th>
                </tr>
            </thead>
            <tbody>
                <!-- Placeholder -->
                <tr id="placeholderItens">
                    <td colspan="5" class="text-center text-muted">Nenhum item adicionado.</td>
                </tr>
            </tbody>
        </table>

        <div class="text-end fs-4 fw-bold my-3">
            Valor Total da Venda: R$ <span id="valor-total-venda">0,00</span>
        </div>

        <!-- Inputs escondidos são adicionados aqui pelo JS -->
        <div id="hidden-itens-container"></div>

        <div class="text-end">
            <button type="submit" id="btn-finalizar" class="btn btn-success btn-lg">Finalizar Venda</button>
        </div>

    </form>
</div>

<?php 
require __DIR__ . '/../layout/footer.php'; 
?>

<!-- Scripts (jQuery e Mask) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
// (JavaScript reescrito em jQuery para consistência)
$(document).ready(function() {
    
    let itensDaVenda = []; 
    let itemCounter = 0; // Para IDs únicos no array JS
    let produtoInfoCache = {}; // Cache para o produto selecionado

    // Aplica máscara de R$
    $('#valor-unitario').mask('000.000,00', { reverse: true });

    // --- LÓGICA DA TAREFA #8 ---

    // 1. Ao selecionar um produto (Requisito #2)
    $('#select-produto').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const preco = selectedOption.data('preco');
        const estoque = selectedOption.data('estoque');
        
        produtoInfoCache = {}; // Limpa o cache
        $('#estoque-aviso').text(''); // Limpa o aviso

        if (selectedOption.val()) {
            // Requisito: Preenche o preço de venda
            const precoFormatado = parseFloat(preco).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).replace('.', ''); // Remove separador de milhar

            $('#valor-unitario').val(precoFormatado);
            $('#valor-unitario').trigger('input'); // Força a máscara

            // Armazena dados do produto
            produtoInfoCache = {
                id: selectedOption.val(),
                nome: selectedOption.data('nome') || selectedOption.text().split(' (Estoque:')[0],
                estoque_atual: parseInt(estoque)
            };
            
            // Foca na quantidade
            $('#quantidade').focus().select();
            validarEstoque(); // Valida o estoque para a quantidade padrão (1)
        } else {
            $('#valor-unitario').val('');
        }
    });

    // 2. Ao digitar a quantidade (Requisito #2 - Validação de Estoque)
    function validarEstoque() {
        if (!produtoInfoCache.id) return true; // Nenhum produto selecionado

        const quantidadeDesejada = parseInt($('#quantidade').val());
        const estoqueDisponivel = produtoInfoCache.estoque_atual;
        
        // (Requisito: não pode ser maior que o estoque)
        if (quantidadeDesejada > estoqueDisponivel) {
            $('#estoque-aviso').text(`Estoque disponível: ${estoqueDisponivel} un.`);
            $('#btn-adicionar').prop('disabled', true); // Desabilita o botão
            return false;
        } else {
            $('#estoque-aviso').text('');
            $('#btn-adicionar').prop('disabled', false); // Habilita o botão
            return true;
        }
    }
    // Adiciona os eventos de validação
    $('#quantidade').on('keyup change', validarEstoque);

    // 3. Ao clicar em "Adicionar Item" (Requisito #2)
    $('#btn-adicionar').on('click', function() {
        // Validações
        if (!produtoInfoCache.id) {
            alert("Selecione um produto.");
            return;
        }
        if (!validarEstoque()) {
            alert("Quantidade maior que o estoque disponível!");
            return;
        }
        
        const quantidade = parseInt($('#quantidade').val());
        let valor_unitario_str = $('#valor-unitario').val().replace(/\./g, '').replace(',', '.');
        const valor_unitario = parseFloat(valor_unitario_str);

        if (quantidade <= 0 || valor_unitario < 0 || !valor_unitario) {
            alert("Quantidade ou Valor Unitário inválidos.");
            return;
        }

        // Adiciona ao array JS
        itensDaVenda.push({
            id_array: itemCounter++,
            id_produto: produtoInfoCache.id,
            produto_nome: produtoInfoCache.nome,
            quantidade: quantidade,
            valor_unitario: valor_unitario
        });

        atualizarGridEVisualizacao();
        limparFormItem();
    });

    // 4. Remover item do grid
    $('#itens-venda-tabela').on('click', '.btn-remover-item', function() {
        const idParaRemover = $(this).data('id-array');
        itensDaVenda = itensDaVenda.filter(item => item.id_array !== idParaRemover);
        atualizarGridEVisualizacao();
    });

    // 5. Função de renderizar o grid e o total (Requisito #3)
    function atualizarGridEVisualizacao() {
        const tabelaBody = $('#itens-venda-tabela tbody');
        tabelaBody.empty();
        let valorTotalCalculado = 0;

        if (itensDaVenda.length === 0) {
            tabelaBody.append('<tr id="placeholderItens"><td colspan="5" class="text-center text-muted">Nenhum item adicionado.</td></tr>');
        } else {
            itensDaVenda.forEach(item => {
                const subtotal = item.quantidade * item.valor_unitario;
                valorTotalCalculado += subtotal;

                const linha = `
                    <tr>
                        <td>${item.produto_nome}</td>
                        <td class="text-center">${item.quantidade}</td>
                        <td class="text-end">R$ ${formatarMoeda(item.valor_unitario)}</td>
                        <td class="text-end">R$ ${formatarMoeda(subtotal)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm btn-remover-item" data-id-array="${item.id_array}">
                                Remover
                            </button>
                        </td>
                    </tr>
                `;
                tabelaBody.append(linha);
            });
        }
        
        // Requisito #4: Cálculo em tempo real
        $('#valor-total-venda').text(formatarMoeda(valorTotalCalculado));
    }

    // 6. Limpar o formulário de item
    function limparFormItem() {
        $('#select-produto').val('');
        $('#valor-unitario').val('');
        $('#quantidade').val('1');
        $('#estoque-aviso').text('');
        produtoInfoCache = {};
        $('#select-produto').focus();
    }

    // 7. Ao "Finalizar Venda" (Requisito #5)
    $('#venda-form').on('submit', function(event) {
        if (!$('#select-cliente').val()) {
            alert("Selecione um cliente.");
            event.preventDefault();
            return;
        }
        if (itensDaVenda.length === 0) {
            alert("Adicione pelo menos um item à venda.");
            event.preventDefault();
            return;
        }
        
        // Limpa inputs antigos
        $('#hidden-itens-container').empty();

        // Cria os inputs escondidos para o POST
        itensDaVenda.forEach((item, index) => {
            // BUG CORRIGIDO: 'id_produto' (esperado pelo Model)
            $('#hidden-itens-container').append(
                `<input type="hidden" name="itens[${index}][id_produto]" value="${item.id_produto}">
                 <input type="hidden" name="itens[${index}][quantidade]" value="${item.quantidade}">
                 <input type="hidden" name="itens[${index}][valor_unitario]" value="${item.valor_unitario}">`
            );
        });
        
        // O Controller/Model irá calcular o valor total, não precisamos enviar.
    });

    // Helper para formatar R$
    function formatarMoeda(valor) {
        return valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
});
</script>