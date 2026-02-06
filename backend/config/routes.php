<?php

use App\Controllers\HomeController;
use App\Controllers\MoviesController;

const ROUTES = [
    [
        'path'       => '/api/movies',
        'controller' => MoviesController::class,
        'method'     => 'list',
        'http'       => 'GET'
    ],
    [
        'path'       => '/api/movies/{id}',
        'controller' => MoviesController::class,
        'method'     => 'show',
        'http'       => 'GET'
    ],
    [
        'path'       => '/api/movies',
        'controller' => MoviesController::class,
        'method'     => 'create',
        'http'       => 'POST'
    ],
    [
        'path'       => '/api/movies/{id}',
        'controller' => MoviesController::class,
        'method'     => 'update',
        'http'       => 'PUT'
    ],
    [
        'path'       => '/api/movies/{id}',
        'controller' => MoviesController::class,
        'method'     => 'delete',
        'http'       => 'DELETE'
    ],

];
