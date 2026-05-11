<?php
// src/Infrastructure/Persistence/MySQLCategoryRepository.php

namespace App\Infrastructure\Persistence;

use App\Infrastructure\Database;
use PDO;

class MySQLCategoryRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Obtiene todas las categorías para llenar los select de los formularios
     */
    public function getAll(): array {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}