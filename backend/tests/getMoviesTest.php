<?php
//tests/test.php
namespace Tests;

use App\Core\Database;

class getMoviesTest extends Database
{
    public function getMovies(): void
    {
        $sql  = "SELECT * FROM movies";
        $stmt = $this->connect()->query($sql);
        while ($row = $stmt->fetch()) {
            echo $row['title'] . '<br>';
        }
    }
}