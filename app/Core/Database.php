<?php

namespace Sofian\MyCinema\Core;

use PDO;

class Database
{
    public function connect(): PDO
    {
        try {
            $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'];
            $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (\PDOException|\TypeError $e) {
            echo '<h4 style="color: RED"> ERROR OCCURED </h4> : <p>' . $e->getMessage() . '</p>';
            die();
        }
    }
}