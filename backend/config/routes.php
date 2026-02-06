<?php

use App\Controllers\HomeController;
use App\Controllers\MoviesController;

const ROUTES = [
    '/'                   => [
        'controller' => HomeController::class,
        'method'     => 'home'
    ],

    /**# Movies Routes */
    '/movies'             => [
        'controller' => MoviesController::class,
        'method'     => 'allMovies'
    ],
    '/movies/delete/{id}' => [
        'controller' => MoviesController::class,
        'method'     => 'delete'
    ],
    '/movies/edit/{id}'   => [
        'controller' => MoviesController::class,
        'method'     => 'edit'
    ]
];
