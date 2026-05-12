<?php
// src/Infrastructure/Persistence/MySQLProductRepository.php

namespace App\Infrastructure\Persistence;

use App\Infrastructure\Database;
use App\Domain\Entities\Product;
use App\Domain\Interfaces\ProductRepositoryInterface;
use PDO;
// Verifica que diga "c.name" y que la tabla sea "categories c"
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.id DESC";
class MySQLProductRepository {
    private $db;

    public function __construct() {
        // Obtenemos la conexión única a través de nuestra clase Database
        $this->db = Database::getConnection();
    }

    /**
     * Obtiene todos los productos de la base de datos con su categoría
     */
    public function getAll(): array {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.id DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Guarda un nuevo producto en la base de datos
     */
    // src/Infrastructure/Persistence/MySQLProductRepository.php

    public function save(Product $product): bool {
        $sql = "INSERT INTO products (name, price, stock, category_id) 
                VALUES (:name, :price, :stock, :cat_id)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name' => $product->name,
            'price' => $product->price,
            'stock' => $product->stock,
            'cat_id' => $product->categoryId
        ]);
    }

    /**
     * Busca un producto por su ID
     */
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();
        return $product ?: null;
    }

    /**
     * Elimina un producto (Solo si el usuario tiene permiso, validado en el controlador)
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Actualiza el stock de un producto (Para la tabla de movimientos)
     */
    public function updateStock(int $id, int $newStock): bool {
        $stmt = $this->db->prepare("UPDATE products SET stock = :stock WHERE id = :id");
        return $stmt->execute(['stock' => $newStock, 'id' => $id]);
    }
}