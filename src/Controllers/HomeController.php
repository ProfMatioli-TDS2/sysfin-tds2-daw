<?php
// App/Controllers/ClienteController.php 
namespace App\Controllers;
class HomeController
{
    public function index()
    {
        require __DIR__ . '/../Views/home/index.php';
    }
}