<?php
// src/Infrastructure/Persistence/MySQLUserRepository.php

namespace App\Infrastructure\Persistence;

use App\Infrastructure\Database;
use PDO;
use App\Domain\Interfaces\UserRepositoryInterface;
class MySQLUserRepository implements UserRepositoryInterface{
    private $db;

    public function __construct() {
        // Conectamos a la base de datos usando nuestra clase central
        $this->db = Database::getConnection();
    }

    /**
     * Busca un usuario por su email para el proceso de Login
     */
    public function findByEmail(string $email): ?array {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                INNER JOIN roles r ON u.role_id = r.id 
                WHERE u.email = :email";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Guarda un nuevo usuario en la base de datos (Registro)
     */
   public function save(array $userData): bool {

        $sql = "INSERT INTO users (username, email, password, role_id) 
                VALUES (:username, :email, :password, 3)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'username' => $userData['username'],
            'email'    => $userData['email'],
            'password' => $userData['password']
        ]);
    }

    /**
     * Método privado para validar duplicados
     */
    private function emailExists(string $email): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Obtiene todos los usuarios (útil para un panel de administración)
     */
    public function getAll(): array {
    $sql = "SELECT u.id, u.username, u.email, r.name as role_name 
            FROM users u 
            JOIN roles r ON u.role_id = r.id";
    return $this->db->query($sql)->fetchAll();
    }
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id AND id != :my_id");
        return $stmt->execute(['id' => $id, 'my_id' => $_SESSION['user_id']]); 
        // El 'my_id' evita que el admin se borre a sí mismo por error.
    }
}