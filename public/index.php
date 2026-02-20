<?php
declare(strict_types=1);

session_start(); // ✅ necesario para repos "memory" persistentes

require dirname(__DIR__) . '/vendor/autoload.php';

$config = require dirname(__DIR__) . '/config/config.php';

use App\Router;
use App\Controllers\HomeController;
use App\Controllers\CategoriesController;
use App\Controllers\ProductsController;

$router = new Router();

// Home
$router->get('/', [HomeController::class, 'index']);

// Categories CRUD
$router->get('/categories', [CategoriesController::class, 'index']);
$router->get('/categories/create', [CategoriesController::class, 'create']);
$router->post('/categories', [CategoriesController::class, 'store']);
$router->get('/categories/edit', [CategoriesController::class, 'edit']); // ?id=1
$router->post('/categories/update', [CategoriesController::class, 'update']);
$router->post('/categories/delete', [CategoriesController::class, 'destroy']);

// Products CRUD
$router->get('/products', [ProductsController::class, 'index']);
$router->get('/products/create', [ProductsController::class, 'create']);
$router->post('/products', [ProductsController::class, 'store']);
$router->get('/products/edit', [ProductsController::class, 'edit']); // ?id=1
$router->post('/products/update', [ProductsController::class, 'update']);
$router->post('/products/delete', [ProductsController::class, 'destroy']);

$method  = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

// recorta base path (xampp subdir)
$basePath = rtrim($config['app']['base_path'] ?? '', '/');
if ($basePath !== '' && str_starts_with($uriPath, $basePath)) {
    $uriPath = substr($uriPath, strlen($basePath)) ?: '/';
}

$response = $router->dispatch($method, $uriPath);
$response->send();