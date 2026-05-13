<?php
// src/Presentation/Controllers/AuthController.php
namespace App\Presentation\Controllers;

use App\Application\UseCases\LoginUseCase;
use App\Application\UseCases\RegisterUserUseCase;

class AuthController
{
    private $userRepo;

    public function __construct($userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function loginView()
    {
        include __DIR__ . '/../Views/login.php';
    }

    public function registerView()
    {
        include __DIR__ . '/../Views/register.php';
    }

    public function login()
    {
        if (!isset($_POST['email'], $_POST['password'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $loginUseCase = new LoginUseCase($this->userRepo);

        $user = $loginUseCase->execute(
            $_POST['email'],
            $_POST['password']
        );

        if ($user) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role_name'];
            $_SESSION['username'] = $user['username'];

            header("Location: index.php?action=list");
            exit;
        }

        $error = "Email o contraseña incorrectos.";

        include '../src/Presentation/Views/login.php';
    }

    public function register()
    {
        if (!isset($_POST['username'], $_POST['email'], $_POST['password'])) {
            header("Location: index.php?action=register");
            exit;
        }

        $registerUseCase = new RegisterUserUseCase($this->userRepo);

        $registerUseCase->execute(
            $_POST['username'],
            $_POST['email'],
            $_POST['password']
        );

        $_SESSION['message'] = "Registro exitoso.";

        header("Location: index.php?action=login");
        exit;
    }

    public function logout()
    {
        session_destroy();

        header("Location: index.php?action=login");
        exit;
    }
}