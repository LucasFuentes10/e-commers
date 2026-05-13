<?php
// public/index.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// =========================
// CONFIGURACIÓN GENERAL
// =========================

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

// =========================
// ENV
// =========================

try {

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

} catch (\Exception $e) {

    die("Error cargando .env");
}

// =========================
// SESIÓN
// =========================

session_start();

// =========================
// IMPORTS
// =========================

use App\Infrastructure\Persistence\MySQLUserRepository;
use App\Infrastructure\Persistence\MySQLProductRepository;
use App\Infrastructure\Persistence\MySQLCategoryRepository;

use App\Presentation\Controllers\AuthController;
use App\Presentation\Controllers\ProductController;
use App\Presentation\Controllers\UserController;

// =========================
// REPOSITORIOS
// =========================

$userRepo = new MySQLUserRepository();

$productRepo = new MySQLProductRepository();

$categoryRepo = new MySQLCategoryRepository();

// =========================
// CONTROLLERS
// =========================

$authController = new AuthController($userRepo);

$productController = new ProductController(
    $productRepo,
    $categoryRepo
);

$userController = new UserController($userRepo);

// =========================
// ROUTER
// =========================

$action = $_GET['action'] ?? 'login';

try {

    switch ($action) {

        // =========================
        // AUTH
        // =========================

        case 'login':
            $authController->loginView();
            break;

        case 'do_login':
            $authController->login();
            break;

        case 'register':
            $authController->registerView();
            break;

        case 'do_register':
            $authController->register();
            break;

        case 'logout':
            $authController->logout();
            break;

        // =========================
        // PRODUCTS
        // =========================

        case 'list':
            $productController->list();
            break;

        case 'add_form':
            $productController->addForm();
            break;

        case 'do_save_product':
            $productController->save();
            break;

        case 'delete_product':
            $productController->delete();
            break;

        // =========================
        // CART
        // =========================

        case 'add_to_cart':
            $productController->addToCart();
            break;

        case 'view_cart':
            $productController->viewCart();
            break;

        case 'remove_from_cart':
            $productController->removeFromCart();
            break;

        case 'checkout':
            $productController->checkout();
            break;

        case 'my_orders':
            $productController->myOrders();
            break;
        // =========================
        // USERS
        // =========================

        case 'manage_users':
            $userController->manage();
            break;

        case 'delete_user':
            $userController->delete();
            break;

        // =========================
        // ADMIN - GESTIÓN DE USUARIOS AVANZADA
        // =========================
        case 'admin_add_user':
            $userController->addUserForm();
            break;

        case 'admin_do_add_user':
            $userController->addUser();
            break;

        // =========================
        // SUPPLIERS
        // =========================

        case 'list_suppliers':
            $productController->listSuppliers();   // reutilizamos el controller por simplicidad
            break;

        case 'add_supplier_form':
            $productController->addSupplierForm();
            break;

        case 'do_save_supplier':
            $productController->saveSupplier();
            break;

        case 'delete_supplier':
            $productController->deleteSupplier();
            break;

        // =========================
        // MY ORDERS
        // =========================

        case 'order_success':
        $productController->orderSuccess();
        break;

        case 'download_pdf':
        $productController->downloadPDF();
        break;
        // =========================
        // DEFAULT
        // =========================

        default:

            header("Location: index.php?action=login");
            exit;
    }

} catch (\Exception $e) {

    echo "<h1>Error en el sistema</h1>";

    echo "<p>" . $e->getMessage() . "</p>";

    echo "<a href='index.php'>Volver al inicio</a>";
}