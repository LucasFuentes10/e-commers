<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Product;
use App\Domain\Interfaces\ProductRepositoryInterface;

class AddProductUseCase
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(
        string $name,
        float $price,
        int $stock,
        ?int $categoryId = null
    ): bool {

        $product = new Product(
            null,
            $name,
            $price,
            $stock,
            $categoryId
        );

        return $this->repository->save($product);
    }
}