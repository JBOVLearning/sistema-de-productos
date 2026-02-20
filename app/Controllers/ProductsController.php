<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Support\Response;
use App\View;
use App\Models\ProductRepository;
use App\Models\CategoryRepository;

final class ProductsController
{
    private function repos(): array
    {
        $config = require dirname(__DIR__, 2) . '/config/config.php';
        $factory = require dirname(__DIR__, 2) . '/config/repository.php';
        return [$config, $factory($config)];
    }

    private function basePath(): string
    {
        $config = require dirname(__DIR__, 2) . '/config/config.php';
        return rtrim($config['app']['base_path'] ?? '', '/');
    }

    public function index(): Response
    {
        [, $repos] = $this->repos();

        /** @var ProductRepository $prods */
        $prods = $repos['products'];

        return Response::html(View::page('products/index', [
            'products' => $prods->all(),
        ]));
    }

    public function create(): Response
    {
        [, $repos] = $this->repos();

        /** @var CategoryRepository $cats */
        $cats = $repos['categories'];

        return Response::html(View::page('products/create', [
            'categories' => $cats->all(),
        ]));
    }

    public function store(): Response
    {
        [, $repos] = $this->repos();
        $basePath = $this->basePath();

        /** @var ProductRepository $prods */
        $prods = $repos['products'];

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));
        $price = (string)($_POST['price'] ?? '0');

        if ($categoryId <= 0 || $name === '') {
            return Response::html("422<br>Datos inválidos", 422);
        }

        $prods->create([
            'category_id' => $categoryId,
            'name' => $name,
            'price' => $price,
        ]);

        return Response::redirect($basePath . '/products');
    }

    public function edit(): Response
    {
        [, $repos] = $this->repos();

        /** @var ProductRepository $prods */
        $prods = $repos['products'];
        /** @var CategoryRepository $cats */
        $cats = $repos['categories'];

        $id = (int)($_GET['id'] ?? 0);
        $product = $prods->find($id);

        if (!$product) {
            return Response::html("404<br>Producto no encontrado", 404);
        }

        return Response::html(View::page('products/edit', [
            'product' => $product,
            'categories' => $cats->all(),
        ]));
    }

    public function update(): Response
    {
        [, $repos] = $this->repos();
        $basePath = $this->basePath();

        /** @var ProductRepository $prods */
        $prods = $repos['products'];

        $id = (int)($_POST['id'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));
        $price = (string)($_POST['price'] ?? '0');

        if ($id <= 0 || $categoryId <= 0 || $name === '') {
            return Response::html("422<br>Datos inválidos", 422);
        }

        $prods->update($id, [
            'category_id' => $categoryId,
            'name' => $name,
            'price' => $price,
        ]);

        return Response::redirect($basePath . '/products');
    }

    public function destroy(): Response
    {
        [, $repos] = $this->repos();
        $basePath = $this->basePath();

        /** @var ProductRepository $prods */
        $prods = $repos['products'];

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $prods->delete($id);
        }

        return Response::redirect($basePath . '/products');
    }
}