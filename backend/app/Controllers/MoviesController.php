<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Movies;
use JetBrains\PhpStorm\NoReturn;

class MoviesController extends Controller
{
    #[NoReturn]
    public function list(): void // GET //
    {
        $this->jsonResponse(Movies::all());
    }

    #[NoReturn]
    public function show($id): void // GET //
    {
        $movie = Movies::find($id);

        if (!$movie) {
            $this->error('Movie not found', 404);
        }

        $this->jsonResponse($movie);
    }

    #[NoReturn]
    public function create(): void
    {
        $data  = $this->getJsonBody();
        $movie = new Movies();

        $movie->setTitle($data['title']);
        $movie->setDescription($data['description']);
        $movie->setDirector($data['director']);
        $movie->setDuration($data['duration']);
        $movie->setReleaseYear($data['release_year']);
        $movie->setGenre($data['genre']);
        $movie->save();

        $this->jsonResponse('Movie created', 201);
    }

    #[NoReturn]
    public function update($id): void // PUT //
    {
        $movie = Movies::find($id);
        if (!$movie) {
            $this->error('Movie not found', 404);
        }

        $data = $this->getJsonBody();


        $movie->setTitle($data['title'] ?? $movie->getTitle());
        $movie->setDescription($data['description'] ?? $movie->getDescription());
        $movie->setDirector($data['director'] ?? $movie->getDirector());
        $movie->setDuration($data['duration'] ?? $movie->getDuration());
        $movie->setReleaseYear($data['release_year'] ?? $movie->getReleaseYear());
        $movie->setGenre($data['genre'] ?? $movie->getGenre());
        $movie->save();

        $this->jsonResponse('Movie updated');
    }

    #[NoReturn]
    public function delete($id): void // DELETE //
    {
        $movie = Movies::find($id);
        if (!$movie) {
            $this->error('Movie not found', 404);
        }
        $movie->delete();
        $this->jsonResponse('Movie deleted');
    }


}