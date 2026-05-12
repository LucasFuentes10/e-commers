<?php
// src/Domain/Entities/Product.php
namespace App\Domain\Entities;

use Exception;

class Product {
    public function __construct(
        public ?int $id,
        public string $name,
        public float $price,
        public int $stock,
        public ?int $categoryId = null,
        public ?string $description = null,
        public int $stockMinimo = 0,
        public ?int $supplierId = null
    ) {
        $this->validate();
    }

    private function validate(): void {
        if (empty($this->name)) {
            throw new Exception("El nombre del producto no puede estar vacío.");
        }
        if ($this->price < 0) {
            throw new Exception("El precio no puede ser un valor negativo.");
        }
        if ($this->stock < 0) {
            throw new Exception("El stock no puede ser negativo.");
        }
    }
}