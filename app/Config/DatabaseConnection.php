<?php

namespace app\Config;

use PDO;
use PDOException;

class DatabaseConnection{

function getConnection() {
    $host = 'localhost';
    $dbname = 'secretaryDb';
    $username = 'alecy-stefany';
    $password = '010203@';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro na conexão: " . $e->getMessage());
    }
}
}