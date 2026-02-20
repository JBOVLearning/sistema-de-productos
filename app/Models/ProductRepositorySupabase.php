<?php
declare(strict_types=1);

namespace App\Models;

final class ProductRepositorySupabase implements ProductRepository
{
    public function __construct(private SupabaseClient $db) {}

    public function all(): array
    {
        // Si tienes FK/relación, puedes pedir join tipo: category:categories(name)
        // Aquí lo dejamos simple para no depender de relaciones.
        $rows = $this->db->get('products', [
            'select' => 'id,category_id,name,price',
            'order'  => 'id.asc',
        ]);

        $out = [];
        foreach ($rows as $r) {
            if (!is_array($r)) continue;
            $out[] = [
                'id' => (int)($r['id'] ?? 0),
                'category_id' => (int)($r['category_id'] ?? 0),
                'name' => (string)($r['name'] ?? ''),
                'price' => (string)($r['price'] ?? '0'),
            ];
        }
        return $out;
    }

    public function find(int $id): ?array
    {
        $rows = $this->db->get('products', [
            'select' => 'id,category_id,name,price',
            'id' => 'eq.' . $id,
            'limit' => 1,
        ]);

        if (!isset($rows[0]) || !is_array($rows[0])) return null;

        return [
            'id' => (int)($rows[0]['id'] ?? 0),
            'category_id' => (int)($rows[0]['category_id'] ?? 0),
            'name' => (string)($rows[0]['name'] ?? ''),
            'price' => (string)($rows[0]['price'] ?? '0'),
        ];
    }

    public function create(array $data): int
    {
        $categoryId = (int)($data['category_id'] ?? 0);
        $name = trim((string)($data['name'] ?? ''));
        $price = (string)($data['price'] ?? '0');

        if ($categoryId <= 0) throw new \InvalidArgumentException('category_id requerido');
        if ($name === '') throw new \InvalidArgumentException('name requerido');

        $rows = $this->db->insert('products', [[
            'category_id' => $categoryId,
            'name' => $name,
            'price' => $price,
        ]]);

        return (int)($rows[0]['id'] ?? 0);
    }

    public function update(int $id, array $data): void
    {
        $categoryId = (int)($data['category_id'] ?? 0);
        $name = trim((string)($data['name'] ?? ''));
        $price = (string)($data['price'] ?? '0');

        if ($categoryId <= 0) throw new \InvalidArgumentException('category_id requerido');
        if ($name === '') throw new \InvalidArgumentException('name requerido');

        $this->db->update('products', [
            'id' => 'eq.' . $id,
        ], [
            'category_id' => $categoryId,
            'name' => $name,
            'price' => $price,
        ]);
    }

    public function delete(int $id): void
    {
        $this->db->delete('products', [
            'id' => 'eq.' . $id,
        ]);
    }
}