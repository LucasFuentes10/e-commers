<?php
// src/Application/UseCases/RegisterUserUseCase.php
namespace App\Application\UseCases;

class RegisterUserUseCase {
    private $userRepo;

    public function __construct($userRepo) {
        $this->userRepo = $userRepo;
    }

    public function execute(string $username, string $email, string $password, int $roleId = 3) {
        // 1. Validar que el email no esté vacío
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Email no válido.");
        }

        // 2. Hashear la contraseña por seguridad
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // 3. Guardar a través del repositorio
        // Bien: Pásale las 3 variables por separado
        return $this->userRepo->save([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ]);
    }
}