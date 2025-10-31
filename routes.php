<?php
return function (FastRoute\RouteCollector $r) {
    
    $r->addRoute(['GET', 'POST'], '/login', ['App\Controllers\AuthController', 'login']);
    $r->addRoute('GET', '/logout', ['App\Controllers\AuthController', 'logout']);
    $r->addRoute(['GET', 'POST'], '/usuarios/criar', ['App\Controllers\UsuarioController', 'create']);

    $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'index']);
    
    $r->addRoute('GET', '/clientes', [ 'App\Controllers\ClienteController', 'index']);

    $r->addRoute('GET', '/fornecedores', ['App\Controllers\FornecedorController', 'index']);
    $r->addRoute(['GET', 'POST'], '/fornecedores/criar', ['App\Controllers\FornecedorController', 'create']);
    $r->addRoute(['GET', 'POST'], '/fornecedores/editar/{id:\d+}', ['App\Controllers\FornecedorController', 'edit']);
    $r->addRoute('POST', '/fornecedores/excluir/{id:\d+}', ['App\Controllers\FornecedorController', 'delete']);
    $r->addRoute('GET', '/fornecedores/relatorio', ['App\Controllers\FornecedorController', 'report']);

    $r->addRoute(['GET', 'POST'], '/compras', ['App\Controllers\CompraController', 'report']);
    $r->addRoute(['GET', 'POST'], '/compras/registrar', ['App\Controllers\CompraController', 'registrar']);
    $r->addRoute(['GET', 'POST'], '/compras/relatorio', ['App\Controllers\CompraController', 'report']);

    $r->addRoute('GET', '/produtos', ['App\Controllers\ProdutoController', 'index']);
    $r->addRoute(['GET', 'POST'], '/produtos/criar', ['App\Controllers\ProdutoController', 'create']);
    $r->addRoute(['GET', 'POST'], '/produtos/editar/{id:\d+}', ['App\Controllers\ProdutoController', 'edit']);
    $r->addRoute('POST', '/produtos/excluir/{id:\d+}', ['App\Controllers\ProdutoController', 'delete']);
    $r->addRoute('GET', '/produtos/relatorio', ['App\Controllers\ProdutoController', 'report']);
};

