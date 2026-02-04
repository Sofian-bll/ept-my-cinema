<?php

namespace App\Core;

class ErrorHandler
{

    private static function renderView(string $view): string
    {
        $viewPath = dirname(__DIR__, 1) . '/Views/' . $view;
        return require dirname(__DIR__, 1) . '/Views/layout.php';
    }

    public static function handle(\Throwable $e): void
    {
        $code = $e->getCode() ?: 500;

        if ($e instanceof \PDOException) {
            // Erreur SQL
            $code = 500;
        } else {
            $code = $e->getCode() ?: 500;
        }

        http_response_code($code);

        if ($_ENV['APP_ENV'] === 'dev') {
            echo "<h1>Fatal Error ($code)</h1>";
            echo '<p><strong>Message :</strong> ' . $e->getMessage() . '</p>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        } else if ($code === 404) {
            self::renderView('error/404.php');
        } else {
            self::renderView('error/500.php');
        }
    }
}