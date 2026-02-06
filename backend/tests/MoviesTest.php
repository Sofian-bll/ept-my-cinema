<?php

namespace tests;

use App\Models\Movies;

class MoviesTest extends Movies
{
    public function testGetAllMovies(): array
    {
            return $this->getAllMovies();
    }
}
