<?php
require_once __DIR__ . '/../vendor/autoload.php';
// Loading the .env var
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use Sofian\MyCinema\Core\Database;

$database = new Database();
$database->connect();

echo '<h1>Hello World</h1>';
echo __DIR__;