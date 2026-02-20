<?php
declare(strict_types=1);

namespace App\Models;

final class CategoryRepositoryMemory implements CategoryRepository
{
    /** @var array<int, array{id:int,name:string}> */
    private array $items = [
        ['id' => 1, 'name' => 'Bebidas'],
        ['id' => 2, 'name' => 'Lácteos'],
    ];

    private int $nextId = 3;

    public function all(): array
    {
        return array_values($this->items);
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
        $name = trim((string)($data['name'] ?? ''));
        if ($name === '') throw new \InvalidArgumentException('name requerido');

        $id = $this->nextId++;
        $this->items[] = ['id' => $id, 'name' => $name];
        return $id;
    }

    public function update(int $id, array $data): void
    {
        $name = trim((string)($data['name'] ?? ''));
        if ($name === '') throw new \InvalidArgumentException('name requerido');

        foreach ($this->items as $i => $it) {
            if ((int)$it['id'] === $id) {
                $this->items[$i] = ['id' => $id, 'name' => $name];
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