<?php
// Arquivo: App/Core/helpers.php

use App\Core\SessionManager;

/**
 * @param array $perfisRequeridos Lista de perfis que podem acessar. Se vazio, apenas logar é suficiente.
 */
function require_auth(array $perfisRequeridos = [])
{
    SessionManager::start();

    if (!SessionManager::isLoggedIn()) {
        header('Location: /login?redirect_url=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }

    if (!empty($perfisRequeridos)) {
        $acessoPermitido = false;
        foreach ($perfisRequeridos as $perfil) {
            if (SessionManager::hasRole($perfil)) {
                $acessoPermitido = true;
                break;
            }
        }

        if (!$acessoPermitido) {
            http_response_code(403);
            echo "<h1>403 - Acesso Negado</h1>";
            echo "<p>Você não tem permissão para acessar esta página.</p>";
            exit;
        }
    }
}
