<?php

namespace App\Core;
require __DIR__ . '/../../config/routes.php';

class Router
{
    private array $routes; // Routes
    private array $availablePaths; // All paths in routes
    private mixed $requestedPath; // Client requested path

    public function __construct()
    {
        $this->routes         = ROUTES;
        $this->availablePaths = array_keys($this->routes);
        $this->requestedPath  = $_GET['path'] ?? '/'; // Si le path not exist -> rediriger
        $this->parseRoutes();
    }

    private function parseRoutes(): void // Si URL Static ou dynamique
    {
        $explodedRequestedPath = $this->explodePath($this->requestedPath);
        $params                = [];

        // Loop sur chaque routes dans config/routes.php
        foreach ($this->availablePaths as $candidatePath) {
            $foundMatch            = true;
            $explodedCandidatePath = $this->explodePath($candidatePath); // Transforme Candidate Path en Array

            // Verifier si le requested Path a le meme nombre de pathPart que ceux présent dans les routes
            if (count($explodedCandidatePath) === count($explodedRequestedPath)) {

                // foreach sur array de Candidate Path ['controller', 'method', '{id}'] / ['movies', 'edit', '42']
                foreach ($explodedRequestedPath as $key => $requestedPathPart) {
                    $candidatePathPart = $explodedCandidatePath[$key];

                    // Si il y as un parameter, l'ajouter a array $params;
                    if ($this->isParam($candidatePathPart)) {
                        $params[substr($candidatePathPart, 1, -1)] = $requestedPathPart;

                        // Si 'Non' sortir de la boucle
                    } elseif ($candidatePathPart !== $requestedPathPart) {
                        $foundMatch = false;
                        break;
                    }
                }

                // Si les routes match définir la nouvelle route
                if ($foundMatch) {
                    $route = $this->routes[$candidatePath];
                    break;
                }
            }
        }

        if (isset($route)) {
            $controller = new $route['controller'];
            $controller->{$route['method']}(...$params);
        } else {
            echo '<h1> Page Not Found <br> 404 </h1>';
        }
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
}