<?php 
// 1. CHAMA O HEADER (que já tem o menu, o <head>, e abre o <main class="container">)
require __DIR__ . '/../layout/header.php'; 
?>

<h1>Registro de Venda</h1>

<form id="venda-form" method="POST" action="<?php echo BASE_URL; ?>index.php?url=/vendas/create">

    <div class="mb-3">
        <label for="select-cliente" class="form-label">Cliente:</label>
        <select id="select-cliente" name="id_cliente" class="form-select" required>
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
                <div class="col-md-6">
                    <label for="select-produto" class="form-label">Produto:</label>
                    <select id="select-produto" class="form-select">
                        <option value="">Selecione um produto...</option>
                        <?php foreach ($produtos as $produto): ?>
                            <option value="<?= htmlspecialchars($produto->id) ?>" 
                                    data-preco="<?= htmlspecialchars($produto->preco_venda) ?>" 
                                    data-estoque="<?= htmlspecialchars($produto->estoque) ?>">
                                <?= htmlspecialchars($produto->nome) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="valor-unitario" class="form-label">Valor Unitário (R$):</label>
                    <input type="number" id="valor-unitario" class="form-control" step="0.01" min="0" readonly>
                </div>
                <div class="col-md-3">
                    <label for="quantidade" class="form-label">Quantidade:</label>
                    <input type="number" id="quantidade" class="form-control" min="1" value="1">
                    <small id="estoque-aviso" class="text-danger d-block mt-1"></small>
                </div>
            </div>
            <div class="mt-3">
                <button type="button" id="btn-adicionar" class="btn btn-primary">Adicionar Item</button>
            </div>
        </div>
    </div>


    <h3>Itens da Venda</h3>
    <table id="itens-venda-tabela" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Produto</th>
                <th>Qtd.</th>
                <th>Vlr. Unitário</th>
                <th>Vlr. Total</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            </tbody>
    </table>

    <div class="text-end fs-4 fw-bold my-3">
        Valor Total da Venda: R$ <span id="valor-total-venda">0.00</span>
    </div>

    <div id="hidden-itens-container"></div>

    <div class="text-end">
        <button type="submit" id="btn-finalizar" class="btn btn-success btn-lg">Finalizar Venda</button>
    </div>

</form>


<script>
    // --- ESTADO DA SUA APLICAÇÃO (TAREFA #8) ---
    let itensDaVenda = []; 
    let produtoInfoCache = {}; // Cache para guardar infos (id, nome, estoque, preco)

    // --- ELEMENTOS DO HTML (DOM) ---
    const form = document.getElementById('venda-form');
    const selectCliente = document.getElementById('select-cliente');
    const selectProduto = document.getElementById('select-produto');
    const inputValorUnitario = document.getElementById('valor-unitario');
    const inputQuantidade = document.getElementById('quantidade');
    const estoqueAviso = document.getElementById('estoque-aviso');
    const btnAdicionar = document.getElementById('btn-adicionar');
    const tabelaItensBody = document.querySelector('#itens-venda-tabela tbody');
    const spanValorTotal = document.getElementById('valor-total-venda');
    const hiddenItensContainer = document.getElementById('hidden-itens-container');

    // --- FUNÇÕES DE LÓGICA (TAREFA #8) ---

    /**
     * FUNÇÃO ATUALIZADA
     * Agora salva o PREÇO no cache (produtoInfoCache)
     */
    function buscarDetalhesProduto() {
        produtoInfoCache = {}; // Limpa o cache
        estoqueAviso.textContent = "";
        const selectedOption = selectProduto.options[selectProduto.selectedIndex];
        
        if (!selectedOption.value) { // Se for "Selecione..."
            inputValorUnitario.value = "";
            return;
        }

        const preco = selectedOption.getAttribute('data-preco');
        const estoque = selectedOption.getAttribute('data-estoque');

        // 1. Preenche o input (que agora é readonly)
        inputValorUnitario.value = parseFloat(preco).toFixed(2);
        
        // 2. Salva TUDO no cache
        produtoInfoCache = {
            id: selectedOption.value,
            nome: selectedOption.text,
            estoque_atual: parseInt(estoque),
            preco_venda: parseFloat(preco) // <-- MUDANÇA: Salva o preço aqui
        };

        validarEstoque();
    }

    function validarEstoque() {
        if (!produtoInfoCache.id) return true; 
        let quantidadeDesejada = parseInt(inputQuantidade.value);
        if (isNaN(quantidadeDesejada)) {
            quantidadeDesejada = 0;
        }
        const estoqueDisponivel = produtoInfoCache.estoque_atual;
        if (quantidadeDesejada > estoqueDisponivel) {
            estoqueAviso.textContent = `Estoque disponível: ${estoqueDisponivel} un.`;
            return false;
        } else {
            estoqueAviso.textContent = ""; 
            return true;
        }
    }

    /**
     * FUNÇÃO ATUALIZADA
     * Pega o valor unitário do 'produtoInfoCache' (seguro)
     * Remove a validação de valor (desnecessária agora)
     */
    function adicionarItem() {
        
        // 1. Valida se um cliente foi selecionado
        if (!selectCliente.value) {
            alert("Por favor, selecione um cliente antes de adicionar itens.");
            selectCliente.focus(); 
            return;
        }

        // 2. Valida se um produto foi selecionado
        if (!produtoInfoCache.id) {
            alert("Selecione um produto.");
            return;
        }

        // 3. Valida Estoque
        if (!validarEstoque()) {
            alert("Quantidade maior que o estoque disponível!");
            inputQuantidade.focus(); 
            return;
        }

        // 4. Valida Quantidade > 0
        const quantidade = parseInt(inputQuantidade.value);
        if (isNaN(quantidade) || quantidade <= 0) {
            alert("A quantidade deve ser um número maior que zero.");
            inputQuantidade.focus();
            return;
        }

        // 5. Pega o Valor Unitário (MUDANÇA AQUI)
        // Pega o valor do cache, não mais do <input>
        const valorUnitario = produtoInfoCache.preco_venda;
        // Não precisamos mais validar o valor, pois ele veio do 'data-preco'

        // Se passou em tudo, cria o item
        const item = {
            produto_id: produtoInfoCache.id,
            produto_nome: produtoInfoCache.nome,
            quantidade: quantidade,
            valor_unitario: valorUnitario // Usa o valor pego do cache
        };

        itensDaVenda.push(item);
        atualizarGridItens();
        atualizarTotalVenda();
        limparFormItem();
    }

    function atualizarGridItens() {
        tabelaItensBody.innerHTML = ""; 
        itensDaVenda.forEach((item, index) => {
            const tr = document.createElement('tr');
            const totalItem = (item.quantidade * item.valor_unitario).toFixed(2);
            tr.innerHTML = `
                <td>${item.produto_nome}</td>
                <td>${item.quantidade}</td>
                <td>R$ ${item.valor_unitario.toFixed(2)}</td>
                <td>R$ ${totalItem}</td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removerItem(${index})">Remover</button></td>
            `;
            tabelaItensBody.appendChild(tr);
        });
    }

    function removerItem(index) {
        itensDaVenda.splice(index, 1);
        atualizarGridItens();
        atualizarTotalVenda();
    }

    function atualizarTotalVenda() {
        let total = 0;
        for (const item of itensDaVenda) {
            total += item.quantidade * item.valor_unitario;
        }
        spanValorTotal.textContent = total.toFixed(2);
    }

    // Limpa o formulário e o cache
    function limparFormItem() {
        selectProduto.value = "";
        inputValorUnitario.value = "";
        inputQuantidade.value = "1";
        estoqueAviso.textContent = "";
        produtoInfoCache = {}; // Limpa o cache (importante)
    }

    function prepararSubmit(event) {
        if (!selectCliente.value) {
            alert("Selecione um cliente.");
            event.preventDefault(); 
            return;
        }
        if (itensDaVenda.length === 0) {
            alert("Adicione pelo menos um item à venda.");
            event.preventDefault();
            return;
        }
        hiddenItensContainer.innerHTML = "";
        itensDaVenda.forEach((item, index) => {
            hiddenItensContainer.insertAdjacentHTML('beforeend',
                `<input type="hidden" name="itens[${index}][produto_id]" value="${item.produto_id}">
                 <input type="hidden" name="itens[${index}][quantidade]" value="${item.quantidade}">
                 <input type="hidden" name="itens[${index}][valor_unitario]" value="${item.valor_unitario}">`
            );
        });
    }

    // --- "OUVINTES" DE EVENTOS (Event Listeners) ---
    selectProduto.addEventListener('change', buscarDetalhesProduto);
    inputQuantidade.addEventListener('keyup', validarEstoque);
    inputQuantidade.addEventListener('change', validarEstoque);
    btnAdicionar.addEventListener('click', adicionarItem);
    form.addEventListener('submit', prepararSubmit);
</script>

<?php 
// 3. CHAMA O FOOTER (que tem o copyright e fecha o </main>, </body> e </html>)
require __DIR__ . '/../layout/footer.php'; 
?>