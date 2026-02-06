<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\ErrorHandler;
use App\Models\Movies;
use JetBrains\PhpStorm\NoReturn;

class MoviesController extends Controller
{
    public function allMovies(): string
    {
        return $this->renderView('movies/allMovies.php', Movies::all());
    }

    #[NoReturn]
    public function delete($id): mixed
    {
        $movie = Movies::find($id);
        $movie?->delete($id);

        return $this->redirectToRoute('/movies');
    }

    public function edit($id)
    {
        $movie = Movies::find($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $movie?->setTitle($_POST['title']);
            $movie?->setDescription($_POST['description']);
            $movie?->setDirector($_POST['director']);
            $movie?->setDuration($_POST['duration']);
            $movie?->setReleaseYear($_POST['release_year']);
            $movie?->save();

            return $this->redirectToRoute('/movies');
        }
        throw new \RuntimeException('Input Error', 422);
    }
}