<?php
// src/Application/UseCases/LoginUseCase.php
namespace App\Application\UseCases;

class LoginUseCase {
    private $authRepo;

    public function __construct($authRepo) {
        $this->authRepo = $authRepo;
    }

    // Cambiamos el retorno de :bool a :?array (puede devolver array o null)
    public function execute(string $email, string $password): ?array {
        $user = $this->authRepo->findByEmail($email);

        // Verificamos el hash
        if ($user && password_verify($password, $user['password'])) {
            // Quitamos los $_SESSION de aquí, los manejaremos en el index.php
            // para que el caso de uso sea "puro" (solo lógica, no estado global)
            return $user;
        }
        
        return null;
    } 
}