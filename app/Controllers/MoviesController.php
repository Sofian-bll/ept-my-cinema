<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Movies;

class MoviesController extends Controller
{
    public function allMovies(): string
    {
        return $this->renderView('movies/allMovies.php', Movies::all());
    }

    public function delete($id): mixed
    {
        $movie = Movies::find($id);
        $movie?->delete($id);

        return $this->redirectToRoute('/movies');
    }
}