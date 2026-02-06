<?php

namespace App\Core;

use JetBrains\PhpStorm\NoReturn;

abstract class Controller
{
    protected function renderView(string $view, array $data = []): string
    {
        $viewPath = dirname(__DIR__, 1) . '/Views/' . $view;
        return require dirname(__DIR__, 1) . '/Views/layout.php';
    }

    #[NoReturn]
    protected function redirectToRoute(string $path, array $params = []): mixed
    {
        $uri = $_SERVER['SCRIPT_NAME'] . '?path=' . $path;

        if (!empty($params)) {
            $strParams = [];
            foreach ($params as $key => $value) {
                $strParams[] = urlencode((string)$key) . '=' . urlencode((string)$value);
            }
            $uri .= '&' . implode('&', $strParams);
        }

        header('Location: ' . $uri);
        die();
    }
}