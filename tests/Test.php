<?php

namespace tests;
require_once './../app/Core/Database.php';

use app\Core\Database;

class Test extends Database
{
    public function getMovies()
    {
        $sql  = "SELECT * FROM movies";
        $stmt = $this->connect()->query($sql);
        while ($row = $stmt->fetch()) {
            echo $row['title'] . '<br>';
        }
    }
}