<?php
// Retorna uma função que define todas as rotas para o dispatcher
return function (FastRoute\RouteCollector $r) {
    //home;
    $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'index']);
    
    //clientes;
    $r->addRoute('GET', '/clientes', [ 'App\Controllers\ClienteController', 'index']);

    // relatorio de vendas
    
    $r->addRoute('GET', '/relatorio-vendas', ['App\Controllers\RelatorioVendasController', 'form']);
    $r->addRoute('POST', '/relatorio-vendas/gerar', ['App\Controllers\RelatorioVendasController', 'gerar']);
    

};