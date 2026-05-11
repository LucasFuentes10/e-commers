<?php

// =========================
// CONFIGURACIÓN GENERAL
// =========================

// Mostrar errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Composer Autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Variables de entorno (.env)
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (\Exception $e) {
    die("Error: No se encontró el archivo .env en la raíz del proyecto.");
}

// Sesión
session_start();


// =========================
// IMPORTS
// =========================

use App\Application\UseCases\LoginUseCase;
use App\Application\UseCases\RegisterUserUseCase;

use App\Infrastructure\Persistence\MySQLProductRepository;
use App\Infrastructure\Persistence\MySQLUserRepository;
use App\Infrastructure\Persistence\MySQLCategoryRepository;


// =========================
// REPOSITORIOS
// =========================

$userRepo = new MySQLUserRepository();
$productRepo = new MySQLProductRepository();
$categoryRepo = new MySQLCategoryRepository();


// =========================
// ROUTER
// =========================

$action = $_GET['action'] ?? 'login';

try {

    switch ($action) {

        // =========================
        // LOGIN
        // =========================

        case 'login':
            include '../src/Presentation/views/login.php';
            break;

        case 'do_login':

            if (isset($_POST['email'], $_POST['password'])) {

                $loginUseCase = new LoginUseCase($userRepo);

                $user = $loginUseCase->execute(
                    $_POST['email'],
                    $_POST['password']
                );

                if ($user !== false && is_array($user)) {

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_role'] = $user['role_name'];
                    $_SESSION['username'] = $user['username'];

                    header("Location: index.php?action=list");
                    exit;

                } else {

                    $error = "Email o contraseña incorrectos.";
                    include '../src/Presentation/views/login.php';
                }
            }

            break;


        // =========================
        // REGISTRO
        // =========================

        case 'register':
            include '../src/Presentation/views/register.php';
            break;

        case 'do_register':

            if (isset($_POST['username'], $_POST['email'], $_POST['password'])) {

                $registerUseCase = new RegisterUserUseCase($userRepo);

                $registerUseCase->execute(
                    $_POST['username'],
                    $_POST['email'],
                    $_POST['password']
                );

                $_SESSION['message'] = "¡Registro exitoso! Ahora inicia sesión.";

                header("Location: index.php?action=login");
                exit;
            }

            break;


        // =========================
        // PRODUCTOS
        // =========================

        case 'list':

            if (!isset($_SESSION['user_id'])) {
                header("Location: index.php");
                exit;
            }

            $products = $productRepo->getAll();

            include '../src/Presentation/views/product_list.php';

            break;


        case 'add_form':

            if (!in_array($_SESSION['user_role'], ['Admin', 'Empleado'])) {
                die("No tienes permisos para esta sección.");
            }

            $categories = $categoryRepo->getAll();

            include '../src/Presentation/views/add_product.php';

            break;


        case 'do_save_product':

            // Verificar permisos
            if (
                !isset($_SESSION['user_role']) ||
                $_SESSION['user_role'] === 'Usuario'
            ) {
                header("Location: index.php?action=login");
                exit;
            }

            // Datos del formulario
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $stock = $_POST['stock'] ?? 0;
            $categoryId = $_POST['category_id'] ?? null;

            // Guardar producto
            if ($name && $price) {

                $productRepo->save(
                    $name,
                    $price,
                    $stock,
                    $categoryId
                );

                header("Location: index.php?action=list");
                exit;

            } else {

                $error = "Todos los campos son obligatorios";

                $categories = $categoryRepo->getAll();

                include '../src/Presentation/views/add_product.php';
            }

            break;


        // =========================
        // CARRITO
        // =========================

        case 'add_to_cart':

            if ($_SESSION['user_role'] !== 'Usuario') {
                die("Solo los clientes pueden comprar.");
            }

            $productId = $_GET['id'];

            // Inicializar carrito
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Agregar producto
            $_SESSION['cart'][] = $productId;

            header("Location: index.php?action=list&msg=Agregado");
            exit;


        case 'view_cart':

            include '../src/Presentation/views/cart.php';

            break;


        // =========================
        // USUARIOS
        // =========================

        case 'manage_users':

            if ($_SESSION['user_role'] !== 'Admin') {
                die("Área exclusiva para Administradores.");
            }

            $users = $userRepo->getAll();

            include '../src/Presentation/views/manage_users.php';

            break;


        // =========================
        // LOGOUT
        // =========================

        case 'logout':

            session_destroy();

            header("Location: index.php?action=login");

            exit;


        // =========================
        // DEFAULT
        // =========================

        default:

            header("Location: index.php?action=login");

            break;
    }

} catch (\Exception $e) {

    echo "<h1>Error en el sistema</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<a href='index.php'>Volver al inicio</a>";
}


// =========================
// DEBUG TEMPORAL
// =========================

if (isset($_GET['action'])) {

    echo "Acción recibida: " . $_GET['action'] . "<br>";
    echo "Rol en sesión: " . ($_SESSION['user_role'] ?? 'NINGUNO') . "<br>";

    if ($_GET['action'] === 'do_save_product') {

        echo "Datos POST: ";
        print_r($_POST);

        die();
    }
}