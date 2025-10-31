<?php
namespace App\Models;

use \PDO;
use \PDOException;

class Perfil
{
    private $conn;

    public function __construct()
    {
        $host = '143.106.241.4'; 
        $dbname = 'matioli';     
        $user = 'bancomatioli'; 
        $pass = 'senhabanco'; 
        
        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
        }
    }

    /**
     * @return array
     */
    public static function getAll(): array
    {
        $model = new self(); 
        try {
            $sql = "SELECT * FROM perfis ORDER BY nome ASC";
            $stmt = $model->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Erro ao buscar perfis: " . $e->getMessage());
            return [];
        }
    }
}
