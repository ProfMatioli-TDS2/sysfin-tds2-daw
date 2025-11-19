<?php
// App/Controllers/HomeController.php 
namespace App\Controllers;

// 1. IMPORTA O SESSION MANAGER
use App\Core\SessionManager;

class HomeController
{

    
    public function index()
    {
        // 2. PROTEÇÃO DO MÉTODO
        // Exige que o usuário esteja logado (qualquer perfil)
        SessionManager::require_auth();
        
        require __DIR__ . '/../Views/home/index.php';
    }
}