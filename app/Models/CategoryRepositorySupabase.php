<?php
declare(strict_types=1);

namespace App\Models;

final class CategoryRepositorySupabase implements CategoryRepository
{
    public function __construct(private SupabaseClient $db) {}

    public function all(): array
    {
        $rows = $this->db->get('categories', [
            'select' => 'id,name',
            'order'  => 'id.asc',
        ]);

        $out = [];
        foreach ($rows as $r) {
            if (!is_array($r)) continue;
            $out[] = [
                'id' => (int)($r['id'] ?? 0),
                'name' => (string)($r['name'] ?? ''),
            ];
        }
        return $out;
    }

    public function find(int $id): ?array
    {
        $rows = $this->db->get('categories', [
            'select' => 'id,name',
            'id' => 'eq.' . $id,
            'limit' => 1,
        ]);

        if (!isset($rows[0]) || !is_array($rows[0])) return null;

        return [
            'id' => (int)($rows[0]['id'] ?? 0),
            'name' => (string)($rows[0]['name'] ?? ''),
        ];
    }

    public function create(array $data): int
    {
        $name = trim((string)($data['name'] ?? ''));
        if ($name === '') throw new \InvalidArgumentException('name requerido');

        $rows = $this->db->insert('categories', [
            ['name' => $name]
        ]);

        $id = (int)($rows[0]['id'] ?? 0);
        return $id;
    }

    public function update(int $id, array $data): void
    {
        $name = trim((string)($data['name'] ?? ''));
        if ($name === '') throw new \InvalidArgumentException('name requerido');

        $this->db->update('categories', [
            'id' => 'eq.' . $id,
        ], [
            'name' => $name,
        ]);
    }

    public function delete(int $id): void
    {
        $this->db->delete('categories', [
            'id' => 'eq.' . $id,
        ]);
    }
}