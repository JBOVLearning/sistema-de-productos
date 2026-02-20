<?php
declare(strict_types=1);

namespace App\Models;

final class ProductRepositoryMemory implements ProductRepository
{
    /** @var array<int, array{id:int,category_id:int,name:string,price:string,category_name?:string}> */
    private array $items = [
        ['id' => 1, 'category_id' => 1, 'name' => 'Inca Kola', 'price' => '3.50'],
        ['id' => 2, 'category_id' => 2, 'name' => 'Leche Gloria', 'price' => '4.20'],
    ];

    private int $nextId = 3;

    public function __construct(private CategoryRepository $categories) {}

    public function all(): array
    {
        $cats = [];
        foreach ($this->categories->all() as $c) {
            $cats[(int)$c['id']] = (string)$c['name'];
        }

        $out = [];
        foreach ($this->items as $p) {
            $p['category_name'] = $cats[(int)$p['category_id']] ?? '---';
            $out[] = $p;
        }
        return $out;
    }

    public function find(int $id): ?array
    {
        foreach ($this->items as $it) {
            if ((int)$it['id'] === $id) return $it;
        }
        return null;
    }

    public function create(array $data): int
    {
        $categoryId = (int)($data['category_id'] ?? 0);
        $name = trim((string)($data['name'] ?? ''));
        $price = (string)($data['price'] ?? '0');

        if ($categoryId <= 0) throw new \InvalidArgumentException('category_id requerido');
        if ($name === '') throw new \InvalidArgumentException('name requerido');

        $id = $this->nextId++;
        $this->items[] = [
            'id' => $id,
            'category_id' => $categoryId,
            'name' => $name,
            'price' => $price,
        ];
        return $id;
    }

    public function update(int $id, array $data): void
    {
        $categoryId = (int)($data['category_id'] ?? 0);
        $name = trim((string)($data['name'] ?? ''));
        $price = (string)($data['price'] ?? '0');

        if ($categoryId <= 0) throw new \InvalidArgumentException('category_id requerido');
        if ($name === '') throw new \InvalidArgumentException('name requerido');

        foreach ($this->items as $i => $it) {
            if ((int)$it['id'] === $id) {
                $this->items[$i] = [
                    'id' => $id,
                    'category_id' => $categoryId,
                    'name' => $name,
                    'price' => $price,
                ];
                return;
            }
        }
    }

    public function delete(int $id): void
    {
        foreach ($this->items as $i => $it) {
            if ((int)$it['id'] === $id) {
                unset($this->items[$i]);
                return;
            }
        }
    }
}