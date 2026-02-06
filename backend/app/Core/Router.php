<?php

namespace App\Core;
require __DIR__ . '/../../config/routes.php';

class Router
{
    private array $routes; // Routes
    private string $requestPath; // Client requested path


    public function __construct()
    {
        $this->routes      = ROUTES;
        $this->requestPath = $_GET['path'] ?? '/'; // Si le path not exist -> rediriger
        $this->parseRoutes();
    }


    private function parseRoutes(): void
    {
        $this->handleCors();

        foreach ($this->routes as $route) {
            if ($this->matchPath($route['path'], $this->requestPath) && $route['http'] === $_SERVER['REQUEST_METHOD']) {

                $params = $this->extractParams($route['path'], $this->requestPath);

                $controller = new $route['controller']();
                $controller->{$route['method']}(...$params);

                return; // On a trouvé, on sort.
            }
        }
        throw new \RuntimeException('Page non trouvée', 404);
    }


    private function explodePath(string $path): array
    {
        return explode('/', rtrim(ltrim($path, '/'), '/'));
    }

    private function isParam(string $candidatePathPart): bool
    {
        $isParam = false;

        if (str_contains($candidatePathPart, '{') && str_contains($candidatePathPart, '}')) {
            $isParam = true;
        }

        return $isParam;
    }

    private function handleCors(): void
    {
        $allowOrigin = $_ENV['ALLOWED_ORIGIN'];

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { // Requête du navigateur qui demande automatiquement les methods à accepter venant d'une url
            header('Content-Type: application/json');
            header("Access-Control-Allow-Origin: $allowOrigin"); // Donne l'autorisation a cette url
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Les méthodes autorisées
            header('Access-Control-Allow-Headers: Content-Type');
            http_response_code(200); // Indique que j'ai bien reçu et traité correctement la requête
            exit();
        }
    }

    private function matchPath(string $routePath, string $requestPath): bool
    {
        $routeSegments = $this->explodePath($routePath);
        // Result: ['api', 'movies', '{id}']
        $requestSegments = $this->explodePath($requestPath);
        // Result: ['api', 'movies', '5']

        // Les deux paths doivent avoir le même nombre de segments
        if (count($routeSegments) !== count($requestSegments)) {
            return false;
        }

        // Comparer segment par segment
        foreach ($requestSegments as $key => $requestSegment) {
            $routeSegment = $routeSegments[$key];

            if ($this->isParam($routeSegment)) { // Si param → on continue
                continue; // passe a l'itération suivante
            }

            // Si différent → échec
            if ($routeSegment !== $requestSegment) {
                return false;
            }
        }

        return true;
    }

    private function extractParams(string $routePath, string $requestPath): array
    {
        $params        = [];
        $routeSegments = $this->explodePath($routePath);
        // Result: ['api', 'movies', '{id}']
        $requestSegments = $this->explodePath($requestPath);
        // Result: ['api', 'movies', '5']


        // Comparer segment par segment
        foreach ($requestSegments as $key => $requestSegment) {
            $routeSegment = $routeSegments[$key];

            if (!$this->isParam($routeSegment)) { // Si non param → on continue
                continue; // passe a l'itération suivante
            }

            $params[substr($routeSegment, 1, -1)] = $requestSegment;
        }

        return $params;
    }

}

