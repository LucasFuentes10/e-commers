<?php
// src/Presentation/Controllers/UserController.php
namespace App\Presentation\Controllers;

class UserController
{
    private $userRepo;

    public function __construct($userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function manage()
    {
        if (
            !isset($_SESSION['user_role']) ||
            $_SESSION['user_role'] !== 'Admin'
        ) {
            die("No autorizado");
        }

        $users = $this->userRepo->getAll();

        include __DIR__ . '/../Views/manage_users.php';
    }

    public function delete()
    {
        if (
            !isset($_SESSION['user_role']) ||
            $_SESSION['user_role'] !== 'Admin'
        ) {
            die("No autorizado");
        }

        $id = $_GET['id'] ?? null;

        if ($id) {
            $this->userRepo->delete((int)$id);
        }

        header("Location: index.php?action=manage_users");
        exit;
    }

    public function addUserForm()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
            die("No autorizado");
        }

        include __DIR__ . '/../Views/admin_add_user.php';
    }

    public function addUser()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
            die("No autorizado");
        }

        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role_id  = (int)($_POST['role_id'] ?? 3); // 3 = Usuario por defecto

        if (empty($username) || empty($email) || empty($password)) {
            $_SESSION['error'] = "Todos los campos son obligatorios.";
            header("Location: index.php?action=admin_add_user");
            exit;
        }

        $registerUseCase = new RegisterUserUseCase($this->userRepo);

        try {
            $registerUseCase->execute($username, $email, $password, $role_id);
            $_SESSION['message'] = "Usuario creado correctamente.";
            header("Location: index.php?action=manage_users");
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: index.php?action=admin_add_user");
        }
        exit;
    }
}