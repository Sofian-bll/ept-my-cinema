<?php

use App\Controllers\MoviesController;
use App\Controllers\RoomsController;
use App\Controllers\ScreeningsController;

const ROUTES = [

    /**
     *# Movies API
     */
    //region Movies API
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
    //endregion

    /**
     *#  Rooms API
     */
    //region Rooms API
    [
        'path'       => '/api/rooms',
        'controller' => RoomsController::class,
        'method'     => 'list',
        'http'       => 'GET'
    ],
    [
        'path'       => '/api/rooms/{id}',
        'controller' => RoomsController::class,
        'method'     => 'show',
        'http'       => 'GET'
    ],
    [
        'path'       => '/api/rooms',
        'controller' => RoomsController::class,
        'method'     => 'create',
        'http'       => 'POST'
    ],
    [
        'path'       => '/api/rooms/{id}',
        'controller' => RoomsController::class,
        'method'     => 'update',
        'http'       => 'PUT'
    ],
    [
        'path'       => '/api/rooms/{id}',
        'controller' => RoomsController::class,
        'method'     => 'delete',
        'http'       => 'DELETE'
    ],
    //endregion

    /**
     *#  Screenings API
     */
    //region Screenings API
    [
        'path'       => '/api/screenings',
        'controller' => ScreeningsController::class,
        'method'     => 'list',
        'http'       => 'GET'
    ],
    [
        'path'       => '/api/screenings/{id}',
        'controller' => ScreeningsController::class,
        'method'     => 'show',
        'http'       => 'GET'
    ],
    [
        'path'       => '/api/screenings',
        'controller' => ScreeningsController::class,
        'method'     => 'create',
        'http'       => 'POST'
    ],
    [
        'path'       => '/api/screenings/{id}',
        'controller' => ScreeningsController::class,
        'method'     => 'update',
        'http'       => 'PUT'
    ],
    [
        'path'       => '/api/screenings/{id}',
        'controller' => ScreeningsController::class,
        'method'     => 'delete',
        'http'       => 'DELETE'
    ],
    //endregion

];