<?php

require_once __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/routes.php';

use Symfony\Component\VarDumper\VarDumper;

function explodePath(string $path): array
{
    return explode('/', rtrim(ltrim($path, '/'), '/'));
}

$path = 'formations/php';


dump(explodePath($path));

