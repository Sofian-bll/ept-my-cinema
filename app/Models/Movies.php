<?php
/** @noinspection ALL */

namespace App\Models;

use App\Core\Database;

class Movies extends Database
{


    protected function getAllMovies()
    {
        try {
            $sql  = 'SELECT * FROM movies';
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            $output = $stmt->fetchAll();
            return $output;
        } catch (\Exception $e) {
            throw new \Exception('Error fetching movies: ' . $e->getMessage());
        }
    }
}