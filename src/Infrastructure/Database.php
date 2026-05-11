<?php
// src/Infrastructure/Database.php

namespace App\Infrastructure;

use PDO;
use PDOException;

class Database {
    private static $instance = null;

    /**
     * Este método estático actúa como un "Singleton"
     * para no abrir mil conexiones a la vez.
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            try {
                // Leemos los datos del archivo .env que creamos antes
                // Dentro de src/Infrastructure/Database.php
                $host = $_ENV['DB_HOST'] ?? 'localhost';
                $db   = $_ENV['DB_NAME'] ?? 'proyecto_lenguaje2';
                $user = $_ENV['DB_USER'] ?? 'root';
                $pass = $_ENV['DB_PASS'] ?? '';
                // Creamos la conexión usando PDO (el estándar seguro de PHP)
                self::$instance = new PDO(
                    "mysql:host=$host;dbname=$db;charset=utf8mb4",
                    $user,
                    $pass,
                    [
                        // Configuraciones de seguridad y errores
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                // Si algo falla (ej: clave mal escrita), te avisará aquí
                die("Error crítico de conexión: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}