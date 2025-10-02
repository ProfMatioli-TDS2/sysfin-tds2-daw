<?php
namespace App\Core;
use PDO;
use PDOException;

define('DB_HOST', '143.106.241.4'); 
define('DB_NAME', 'matioli'); // Nome do banco de dados 
define('DB_USER', 'bancomatioli'); // Usuario do banco de dados
define('DB_PASS', 'senhabanco'); // Senha do banco de dados 

class Database
{
    private static $instance = null;
    public static function getConnection()
    {
        if (self::$instance === null) {
             try {
                $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
                self::$instance = new PDO($dsn, DB_USER, DB_PASS);
                self::$instance->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION
                );
            } catch (PDOException $e) {
                die('Erro de conexão: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}