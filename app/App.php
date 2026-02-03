<?php

namespace App;

use App\Core\Router;
use App\Controllers\HomeController;
use Dotenv\Dotenv;

class App
{
    protected string $controller = 'home';
    protected string $method = 'index';
    protected array $params = [];

    public function __construct()
    {
        // Loading the .env var
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        // Display Error
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    public function run(): void
    {
        new Router();
    }
}