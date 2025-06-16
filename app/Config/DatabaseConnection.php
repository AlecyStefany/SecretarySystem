<?php

namespace app\Config;

use Dotenv\Dotenv;
use PDO;
use PDOException;

class DatabaseConnection
{
    function getConnection()
    {

        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $username = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }
}
