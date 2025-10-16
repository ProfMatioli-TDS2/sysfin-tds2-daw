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
    $r->addRoute(['GET', 'POST'], '/compras/registrar', ['App\Controllers\CompraController', 'registrar']);
    $r->addRoute(['GET', 'POST'], '/compras/relatorio', ['App\Controllers\CompraController', 'report']);
};