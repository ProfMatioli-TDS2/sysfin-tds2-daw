<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SysFin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/estilo.css">
</head>

<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-light bg-custom-color mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL;?>/index.php?url=/">SysFin</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            
            <?php if (\App\Core\SessionManager::isLoggedIn()): ?>
            
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Cadastros
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (\App\Core\SessionManager::hasRole('Administrador') || \App\Core\SessionManager::hasRole('Tesoureiro')): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/plano-contas">Plano de Contas</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/clientes">Clientes</a></li>
                            <?php endif; ?>
                            
                            <?php if (\App\Core\SessionManager::hasRole('Administrador') || \App\Core\SessionManager::hasRole('Tesoureiro') || \App\Core\SessionManager::hasRole('Vendedor')): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/fornecedores">Fornecedores</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/produtos">Produtos</a></li>
                            <?php endif; ?>

                            <?php if (\App\Core\SessionManager::hasRole('Administrador')): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/users">Usuários</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/profiles">Perfis de Acesso</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Lançamentos
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (\App\Core\SessionManager::hasRole('Administrador') || \App\Core\SessionManager::hasRole('Tesoureiro') || \App\Core\SessionManager::hasRole('Vendedor')): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/compras/registrar">Compras</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/vendas/create">Vendas</a></li>
                            <?php endif; ?>
                            
                            <?php if (\App\Core\SessionManager::hasRole('Administrador') || \App\Core\SessionManager::hasRole('Tesoureiro')): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/lancamento-manual-caixa">Lançamentos Manuais no Caixa</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Relatorios
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (\App\Core\SessionManager::hasRole('Administrador') || \App\Core\SessionManager::hasRole('Tesoureiro')): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/clientes/relatorio" target="_blank">Cliente</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/plano-contas/relatorio">Plano de Contas</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/compras/relatorio">Compras</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/vendas/relatorio">Vendas</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/relatorio-movimento-caixa">Movimento de Caixa</a></li>
                            <?php endif; ?>

                            <?php if (\App\Core\SessionManager::hasRole('Administrador') || \App\Core\SessionManager::hasRole('Tesoureiro') || \App\Core\SessionManager::hasRole('Vendedor')): ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/fornecedores/relatorio" target="_blank">Fornecedor</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/relatorio-produtos" target="_blank">Produtos</a></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/relatorio-estoque">Estoque</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>

                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-fill"></i> Olá, <?php echo htmlspecialchars(\App\Core\SessionManager::getUser()['nome'] ?? 'Usuário'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL;?>/index.php?url=/logout">Sair <i class="bi bi-box-arrow-right"></i></a></li>
                        </ul>
                    </li>
                </ul>

            <?php else: ?>
            
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL;?>/index.php?url=/login">
                            <i class="bi bi-person-fill"></i> Entrar
                        </a>
                    </li>
                </ul>
                
            <?php endif; ?>
            </div>
    </div>
</nav>

<main class="container flex-grow-1">