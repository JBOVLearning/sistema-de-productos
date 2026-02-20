<?php
declare(strict_types=1);

return [
    'app' => [
        'base_path' => '/SistemaDeProductos', // AJUSTA si tu carpeta se llama distinto
    ],
    'supabase' => [
        'url' => $_ENV['SUPABASE_URL'] ?? '',
        'anon_key' => $_ENV['SUPABASE_ANON_KEY'] ?? '',
    ],
];