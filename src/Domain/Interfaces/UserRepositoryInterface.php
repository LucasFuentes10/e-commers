<?php
// src/Domain/Interfaces/UserRepositoryInterface.php
namespace App\Domain\Interfaces;

interface UserRepositoryInterface {
    public function getByEmail(string $email): ?array;
    public function save(array $userData): void;
}