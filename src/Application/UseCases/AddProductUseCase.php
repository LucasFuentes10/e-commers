<?php
namespace App\Application\UseCases;

use App\Domain\Entities\Product;

class AddProductUseCase {
    private $repository;

    public function __construct($repository) {
        $this->repository = $repository;
    }

    public function execute($name, $price, $stock) {
        // La entidad Product valida internamente (precio > 0, etc.)
        $product = new Product(null, $name, $price, $stock);
        
        // El repositorio lo guarda en la DB
        return $this->repository->save($product);
    }
}