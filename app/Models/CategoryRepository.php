<?php
declare(strict_types=1);

namespace App\Models;

interface CategoryRepository
{
    public function all(): array;
    public function find(int $id): ?array;
    public function create(array $data): int;
    public function update(int $id, array $data): void;
    public function delete(int $id): void;
}