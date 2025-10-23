<?php
// Retorna uma função que define todas as rotas para o dispatcher.
return function (FastRoute\RouteCollector $r) {
    //home;
    $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'index']);
    
    //clientes;
    $r->addRoute('GET', '/clientes', [ 'App\Controllers\ClienteController', 'index']);


    //fornecedores;
    $r->addRoute('GET', '/fornecedores', ['App\Controllers\FornecedorController', 'index']);
    $r->addRoute(['GET', 'POST'], '/fornecedores/criar', ['App\Controllers\FornecedorController', 'create']);
    $r->addRoute(['GET', 'POST'], '/fornecedores/editar/{id:\d+}', ['App\Controllers\FornecedorController', 'edit']);
    $r->addRoute('POST', '/fornecedores/excluir/{id:\d+}', ['App\Controllers\FornecedorController', 'delete']);
    $r->addRoute('GET', '/fornecedores/relatorio', ['App\Controllers\FornecedorController', 'report']);

    //compras
    // Linha corrigida
    $r->addRoute(['GET', 'POST'], '/compras', ['App\Controllers\CompraController', 'report']);
    $r->addRoute(['GET', 'POST'], '/compras/registrar', ['App\Controllers\CompraController', 'registrar']);
    $r->addRoute(['GET', 'POST'], '/compras/relatorio', ['App\Controllers\CompraController', 'report']);

    //produtos; essa é a parte de produtos
    $r->addRoute('GET', '/produtos', ['App\Controllers\ProdutoController', 'index']);
    $r->addRoute(['GET', 'POST'], '/produtos/criar', ['App\Controllers\ProdutoController', 'create']);
    $r->addRoute(['GET', 'POST'], '/produtos/editar/{id:\d+}', ['App\Controllers\ProdutoController', 'edit']);
    $r->addRoute('GET', '/produtos/excluir/{id:\d+}', ['App\Controllers\ProdutoController', 'delete']);
    $r->addRoute('GET', '/produtos/relatorio', ['App\Controllers\ProdutoController', 'report']);

    //vendas; (TAREFA #8 e #9)
    $r->addRoute('GET', '/vendas', ['App\Controllers\VendaController', 'index']);
    $r->addRoute(['GET', 'POST'], '/vendas/create', ['App\Controllers\VendaController', 'create']);
    $r->addRoute('GET', '/vendas/details/{id:\d+}', ['App\Controllers\VendaController', 'details']);
};