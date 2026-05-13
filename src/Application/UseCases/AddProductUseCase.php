<?php
// src/Application/UseCases/AddProductUseCase.php

namespace App\Application\UseCases;

use App\Domain\Entities\Product;

class AddProductUseCase {
    private $repository;

    public function __construct($repository) {
        $this->repository = $repository;
    }

    public function execute(
        string $name, 
        float $price, 
        int $stock, 
        ?int $categoryId = null, 
        ?int $supplierId = null, 
        ?string $description = null
    ): bool { 
        
        $product = new Product(
            null, 
            $name, 
            $price, 
            $stock, 
            $categoryId, 
            $description, 
            0, 
            $supplierId
        );
        
        return $this->repository->save($product); 
    }
}