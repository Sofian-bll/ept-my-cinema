<?php

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    public function connect(): PDO
    {
        try {
            // Config file
            $config = require __DIR__ . '/../../config/database.php';

            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']}";
            $pdo = new PDO($dsn, $config['user'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (\PDOException|\TypeError $e) {
            throw new PDOException('Database connection failed: ' . $e->getMessage());
        }
    }
}