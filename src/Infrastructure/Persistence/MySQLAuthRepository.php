<?php
// src/Infrastructure/Persistence/MySQLAuthRepository.php
namespace App\Infrastructure\Persistence;

use App\Infrastructure\Database;

class MySQLAuthRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function findByEmail(string $email): ?array {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        
        return $user ?: null;
    }
    public function save(array $data): bool {
        $sql = "INSERT INTO users (username, email, password, role_id) 
                VALUES (:username, :email, :password, :role_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
}