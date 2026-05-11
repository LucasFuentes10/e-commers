<?php
// src/Application/UseCases/UpdateStock.php
namespace App\Application\UseCases;

class UpdateStock {
    public function execute(int $productId, int $quantity, string $type, int $userId) {
        // 1. Actualizar el stock en la tabla 'products'
        // 2. Insertar el registro en 'stock_movements' para saber quién movió la mercadería.
        
        // Esto da trazabilidad total: "El usuario Juan sacó 10 unidades de Tornillos el martes".
    }
}