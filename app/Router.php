<?php
declare(strict_types=1);

namespace App;

use App\Support\Response;

final class Router
{
    /** @var array<string, array<string, array{class-string, string}>> */
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$this->n($path)] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$this->n($path)] = $handler;
    }

    public function dispatch(string $method, string $path): Response
    {
        $method = strtoupper($method);
        $path   = $this->n($path);

        $handler = $this->routes[$method][$path] ?? null;

        if (!$handler) {
            return Response::html(
                "404<br>No existe la ruta: " . htmlspecialchars($path),
                404
            );
        }

        [$class, $action] = $handler;
        $controller = new $class();

        $result = $controller->$action();

        if ($result instanceof Response) {
            return $result;
        }

        if (is_string($result)) {
            return Response::html($result);
        }

        return Response::html("500<br>Retorno inválido del controller.", 500);
    }

    private function n(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '//' ? '/' : $path;
    }
}