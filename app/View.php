<?php
declare(strict_types=1);

namespace App;

final class View
{
    public static function render(string $view, array $data = []): string
    {
        $viewFile = dirname(__DIR__) . '/app/Views/' . ltrim($view, '/') . '.php';
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("Vista no encontrada: {$viewFile}");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        return (string) ob_get_clean();
    }

    public static function page(string $view, array $data = []): string
    {
        $config = require dirname(__DIR__) . '/config/config.php';
        $basePath = rtrim($config['app']['base_path'] ?? '', '/');

        // ✅ 1) Inyectar basePath también a la vista hija
        $data['basePath'] = $basePath;

        $content = self::render($view, $data);

        // ✅ 2) Layout también lo recibe
        return self::render('layout', [
            'content'  => $content,
            'basePath' => $basePath,
        ]);
    }
}