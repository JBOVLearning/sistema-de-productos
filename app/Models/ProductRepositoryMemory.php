<?php
declare(strict_types=1);

namespace App\Models;

final class ProductRepositoryMemory implements ProductRepository
{
    private const KEY_ITEMS = 'mem.products.items';
    private const KEY_NEXT  = 'mem.products.nextId';

    public function __construct(private CategoryRepository $categories)
    {
        if (!isset($_SESSION[self::KEY_ITEMS], $_SESSION[self::KEY_NEXT])) {
            $_SESSION[self::KEY_ITEMS] = [
                ['id' => 1, 'category_id' => 1, 'name' => 'Inca Kola', 'price' => '3.50'],
                ['id' => 2, 'category_id' => 2, 'name' => 'Leche Gloria', 'price' => '4.20'],
            ];
            $_SESSION[self::KEY_NEXT] = 3;
        }
    }

    public function all(): array
    {
        $cats = [];
        foreach ($this->categories->all() as $c) {
            $cats[(int)$c['id']] = (string)$c['name'];
        }

        $out = [];
        foreach ($_SESSION[self::KEY_ITEMS] as $p) {
            $p['category_name'] = $cats[(int)$p['category_id']] ?? '---';
            $out[] = $p;
        }
        return $out;
    }

    public function find(int $id): ?array
    {
        foreach ($_SESSION[self::KEY_ITEMS] as $it) {
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

        $id = (int)$_SESSION[self::KEY_NEXT];
        $_SESSION[self::KEY_NEXT] = $id + 1;

        $_SESSION[self::KEY_ITEMS][] = [
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

        foreach ($_SESSION[self::KEY_ITEMS] as $i => $it) {
            if ((int)$it['id'] === $id) {
                $_SESSION[self::KEY_ITEMS][$i] = [
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
        foreach ($_SESSION[self::KEY_ITEMS] as $i => $it) {
            if ((int)$it['id'] === $id) {
                unset($_SESSION[self::KEY_ITEMS][$i]);
                $_SESSION[self::KEY_ITEMS] = array_values($_SESSION[self::KEY_ITEMS]);
                return;
            }
        }
    }
}