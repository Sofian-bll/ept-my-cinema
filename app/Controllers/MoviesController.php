<?php

namespace App\Controllers;

use App\Models\Movies;

class MoviesController extends Movies
{
    /**
     * @throws \Exception
     */
    public function getAllMovies()
    {
        require '../views/MoviesVue';
        return parent::getAllMovies();
    }
}