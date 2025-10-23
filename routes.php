<?php
// Retorna uma função que define todas as rotas para o dispatcher
return function (FastRoute\RouteCollector $r) {
    //home;
    $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'index']);
        
    //clientes;
    $r->addRoute('GET', '/clientes', [ 'App\Controllers\ClienteController', 'index']);
    $r->addRoute(['GET', 'POST'], '/clientes/criar', ['App\Controllers\ClienteController', 'create']);
    $r->addRoute(['GET', 'POST'], '/clientes/editar/{id:\d+}', ['App\Controllers\ClienteController', 'edit']);
    $r->addRoute('POST', '/clientes/excluir/{id:\d+}', ['App\Controllers\ClienteController', 'delete']);
    $r->addRoute('GET', '/clientes/relatorio', ['App\Controllers\ClienteController', 'report']);

    //fornecedores;
    $r->addRoute('GET', '/fornecedores', ['App\Controllers\FornecedorController', 'index']);
    $r->addRoute(['GET', 'POST'], '/fornecedores/criar', ['App\Controllers\FornecedorController', 'create']);
    $r->addRoute(['GET', 'POST'], '/fornecedores/editar/{id:\d+}', ['App\Controllers\FornecedorController', 'edit']);
    $r->addRoute('POST', '/fornecedores/excluir/{id:\d+}', ['App\Controllers\FornecedorController', 'delete']);
    $r->addRoute('GET', '/fornecedores/relatorio', ['App\Controllers\FornecedorController', 'report']);

        //plano de contas;
    $r->addRoute('GET', '/plano-contas', [ 'App\Controllers\PlanoContaController', 'index']);
    $r->addRoute(['GET', 'POST'], '/plano-contas/criar', ['App\Controllers\PlanoContaController', 'create']);
    $r->addRoute(['GET', 'POST'], '/plano-contas/editar/{id:\d+}', ['App\Controllers\PlanoContaController', 'edit']);
    $r->addRoute('POST', '/plano-contas/excluir/{id:\d+}', ['App\Controllers\PlanoContaController', 'delete']);
    $r->addRoute('GET', '/plano-contas/relatorio', ['App\Controllers\PlanoContaController', 'report']);

    //compras
    $r->addRoute(['GET', 'POST'], '/compras', ['App\Controllers\CompraController', 'report']);
    $r->addRoute(['GET', 'POST'], '/compras/registrar', ['App\Controllers\CompraController', 'registrar']);
    $r->addRoute(['GET', 'POST'], '/compras/relatorio', ['App\Controllers\CompraController', 'report']);

    //produtos; essa é a parte de produtos
    $r->addRoute('GET', '/produtos', ['App\Controllers\ProdutoController', 'index']);
    $r->addRoute(['GET', 'POST'], '/produtos/criar', ['App\Controllers\ProdutoController', 'create']);
    $r->addRoute(['GET', 'POST'], '/produtos/editar/{id:\d+}', ['App\Controllers\ProdutoController', 'edit']);
    $r->addRoute('GET', '/produtos/excluir/{id:\d+}', ['App\Controllers\ProdutoController', 'delete']);
    $r->addRoute('GET', '/produtos/relatorio', ['App\Controllers\ProdutoController', 'report']);

    //vendas
    $r->addRoute('GET', '/vendas', ['App\Controllers\VendaController', 'index']);
    $r->addRoute(['GET', 'POST'], '/vendas/create', ['App\Controllers\VendaController', 'create']);
    $r->addRoute('GET', '/vendas/details/{id:\d+}', ['App\Controllers\VendaController', 'details']);
    $r->addRoute('GET', '/vendas/relatorio', ['App\Controllers\VendaController', 'report']);
  
    // Estoque
    $r->addRoute('GET', '/relatorio-estoque', ['App\Controllers\RelatorioEstoqueController', 'index']);

    //profiles
    $r->addRoute('GET', '/profiles', ['App\Controllers\ProfileController', 'index']);
    $r->addRoute(['GET', 'POST'], '/profiles/criar', ['App\Controllers\ProfileController', 'create']);
    $r->addRoute(['GET', 'POST'], '/profiles/editar/{id:\d+}', ['App\Controllers\ProfileController', 'edit']);
    $r->addRoute('GET', '/profiles/delete/{id:\d+}', ['App\Controllers\ProfileController', 'delete']);

    //users
    $r->addRoute('GET', '/users', ['App\Controllers\UserController', 'index']);
    $r->addRoute(['GET', 'POST'], '/users/criar', ['App\Controllers\UserController', 'create']);
    $r->addRoute(['GET', 'POST'], '/users/editar/{id:\d+}', ['App\Controllers\UserController', 'edit']);
    $r->addRoute('GET', '/users/delete/{id:\d+}', ['App\Controllers\UserController', 'delete']);
};