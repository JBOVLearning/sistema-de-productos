<?php
declare(strict_types=1);

use App\Models\CategoryRepository;
use App\Models\ProductRepository;

use App\Models\CategoryRepositoryMemory;
use App\Models\ProductRepositoryMemory;

use App\Models\SupabaseClient;
use App\Models\CategoryRepositorySupabase;
use App\Models\ProductRepositorySupabase;

return function(array $config): array {

    // ✅ CAMBIA SOLO ESTO
    $driver = 'memory';
    // $driver = 'supabase';

    if ($driver === 'supabase') {
        $client = new SupabaseClient(
            $config['supabase']['url'] ?? '',
            $config['supabase']['anon_key'] ?? '',
        );

        return [
            'categories' => new CategoryRepositorySupabase($client),
            'products'   => new ProductRepositorySupabase($client),
        ];
    }

    $catRepo = new CategoryRepositoryMemory();
    $prodRepo = new ProductRepositoryMemory($catRepo);

    return [
        'categories' => $catRepo,
        'products'   => $prodRepo,
    ];
};