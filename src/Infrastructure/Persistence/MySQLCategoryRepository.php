<?php
// src/Infrastructure/Persistence/MySQLCategoryRepository.php

namespace App\Infrastructure\Persistence;

use App\Infrastructure\Database;
use PDO;
use App\Domain\Interfaces\CategoryRepositoryInterface;

class MySQLCategoryRepository implements CategoryRepositoryInterface {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Obtiene todas las categorías para llenar los select de los formularios
     */
    public function getAll(): array {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}