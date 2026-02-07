<?php

namespace Tests;

use App\Controllers\MoviesController;
use JetBrains\PhpStorm\NoReturn;

class MoviesTest extends MoviesController
{
    #[NoReturn]
    public function testGetAllMovies(): null
    {
            $this->list();
    }
}
