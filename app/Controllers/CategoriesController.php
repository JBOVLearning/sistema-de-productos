<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Support\Response;
use App\View;
use App\Models\CategoryRepository;

final class CategoriesController
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
        /** @var CategoryRepository $cats */
        $cats = $repos['categories'];

        return Response::html(View::page('categories/index', [
            'categories' => $cats->all(),
        ]));
    }

    public function create(): Response
    {
        return Response::html(View::page('categories/create'));
    }

    public function store(): Response
    {
        [, $repos] = $this->repos();
        $basePath = $this->basePath();

        /** @var CategoryRepository $cats */
        $cats = $repos['categories'];

        $name = trim((string)($_POST['name'] ?? ''));
        if ($name === '') {
            return Response::html(View::page('categories/create', [
                'error' => 'Nombre requerido',
            ]), 422);
        }

        $cats->create(['name' => $name]);

        return Response::redirect($basePath . '/categories');
    }

    public function edit(): Response
    {
        [, $repos] = $this->repos();
        /** @var CategoryRepository $cats */
        $cats = $repos['categories'];

        $id = (int)($_GET['id'] ?? 0);
        $category = $cats->find($id);

        if (!$category) {
            return Response::html("404<br>Categoría no encontrada", 404);
        }

        return Response::html(View::page('categories/edit', [
            'category' => $category,
        ]));
    }

    public function update(): Response
    {
        [, $repos] = $this->repos();
        $basePath = $this->basePath();

        /** @var CategoryRepository $cats */
        $cats = $repos['categories'];

        $id = (int)($_POST['id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));

        if ($id <= 0 || $name === '') {
            return Response::html("422<br>Datos inválidos", 422);
        }

        $cats->update($id, ['name' => $name]);

        return Response::redirect($basePath . '/categories');
    }

    public function destroy(): Response
    {
        [, $repos] = $this->repos();
        $basePath = $this->basePath();

        /** @var CategoryRepository $cats */
        $cats = $repos['categories'];

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $cats->delete($id);
        }

        return Response::redirect($basePath . '/categories');
    }
}