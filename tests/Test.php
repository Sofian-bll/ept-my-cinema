<?php

namespace tests;

use Sofian\MyCinema\Core\Database;

class Test extends Database
{
    public function getMovies(): void
    {
        $sql  = "SELECT * FROM movies";
        $stmt = $this->connect()->query($sql);
        while ($row = $stmt->fetch()) {
            echo $row['title'] . ' released in ' . $row['release_year'] . '<br>';
        }
    }
}