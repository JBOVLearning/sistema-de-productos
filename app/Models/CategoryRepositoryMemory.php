<?php
declare(strict_types=1);

namespace App\Models;

final class CategoryRepositoryMemory implements CategoryRepository
{
    private const KEY_ITEMS = 'mem.categories.items';
    private const KEY_NEXT  = 'mem.categories.nextId';

    public function __construct()
    {
        if (!isset($_SESSION[self::KEY_ITEMS], $_SESSION[self::KEY_NEXT])) {
            $_SESSION[self::KEY_ITEMS] = [
                ['id' => 1, 'name' => 'Bebidas'],
                ['id' => 2, 'name' => 'Lácteos'],
            ];
            $_SESSION[self::KEY_NEXT] = 3;
        }
    }

    public function all(): array
    {
        return array_values($_SESSION[self::KEY_ITEMS]);
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
        $name = trim((string)($data['name'] ?? ''));
        if ($name === '') throw new \InvalidArgumentException('name requerido');

        $id = (int)$_SESSION[self::KEY_NEXT];
        $_SESSION[self::KEY_NEXT] = $id + 1;

        $_SESSION[self::KEY_ITEMS][] = ['id' => $id, 'name' => $name];
        return $id;
    }

    public function update(int $id, array $data): void
    {
        $name = trim((string)($data['name'] ?? ''));
        if ($name === '') throw new \InvalidArgumentException('name requerido');

        foreach ($_SESSION[self::KEY_ITEMS] as $i => $it) {
            if ((int)$it['id'] === $id) {
                $_SESSION[self::KEY_ITEMS][$i]['name'] = $name;
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