<?php

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

        include '../src/Presentation/views/manage_users.php';
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
}