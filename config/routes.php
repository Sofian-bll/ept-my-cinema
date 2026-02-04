<?php

use App\Controllers\HomeController;
use App\Controllers\MoviesController;

const ROUTES = [
    '/'                   => [
        'controller' => HomeController::class,
        'method'     => 'home'
    ],
    '/movies'             => [
        'controller' => MoviesController::class,
        'method'     => 'allMovies'
    ],
    '/movies/delete/{id}' => [
        'controller' => MoviesController::class,
        'method' => 'delete'
    ]
];
