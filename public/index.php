<?php
// Força a exibição de todos os erros (essencial em desenvolvimento)
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Carrega o autoloader do Composer
require_once __DIR__ . '/../vendor/autoload.php';
// Define a URL base para construir links
define('BASE_URL', '/sysfin-4dsn');
// Cria o dispatcher do FastRoute, alimentando-o com nossas definições de rotas
$dispatcher = FastRoute\simpleDispatcher(
    require __DIR__ . '/../routes.php'
);
// Pega o método HTTP e a URI da requisição
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_GET['url'] ?? '/';
// Adiciona uma barra no início se não houver
if (isset($uri[0]) && $uri[0] !== '/') {
    $uri = '/' . $uri;
}

// Despacha a rota, obtendo o resultado
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
// Trata o resultado do dispatcher
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Erro 404: Página não encontrada.';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo 'Erro 405: Método não permitido.';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $controllerClass = $handler[0];
        $methodName = $handler[1];
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            call_user_func_array([$controller, $methodName], $vars);
        } else {
            http_response_code(500);
            echo "Erro 500: Controller '{$controllerClass}' não encontrado.";
        }
        break;
}