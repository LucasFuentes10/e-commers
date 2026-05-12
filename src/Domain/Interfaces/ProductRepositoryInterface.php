<?php

namespace App\Domain\Interfaces;

use App\Domain\Entities\Product;

interface ProductRepositoryInterface
{
    public function getAll(): array;

    public function findById(int $id): ?array;

    public function save(Product $product): bool;

    public function delete(int $id): bool;

    public function updateStock(int $id, int $newStock): bool;
}