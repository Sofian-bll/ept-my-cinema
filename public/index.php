<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../tests/Test.php';
use tests\Test;

// Loading the .env var
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$testObj = new Test();
$testObj->getMovies();