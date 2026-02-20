<?php
declare(strict_types=1);

$basePath = $_ENV['APP_BASE_PATH'] ?? getenv('APP_BASE_PATH') ?: '';

return [
    'app' => [
        'base_path' => $basePath, // local: /SistemaDeProductos | railway: ''
    ],
    'supabase' => [
        'url' => $_ENV['SUPABASE_URL'] ?? '',
        'anon_key' => $_ENV['SUPABASE_ANON_KEY'] ?? '',
    ],
];