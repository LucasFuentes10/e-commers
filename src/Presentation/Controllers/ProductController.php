<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\AddProductUseCase;

class ProductController
{
    private $productRepo;
    private $categoryRepo;

    public function __construct($productRepo, $categoryRepo)
    {
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
    }

    public function list()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $products = $this->productRepo->getAll();

        include '../src/Presentation/views/product_list.php';
    }

    public function addForm()
    {
        if (
            !isset($_SESSION['user_role']) ||
            !in_array($_SESSION['user_role'], ['Admin', 'Empleado'])
        ) {
            die("No autorizado");
        }

        $categories = $this->categoryRepo->getAll();

        include '../src/Presentation/views/add_product.php';
    }

    public function save()
    {
        if (
            !isset($_SESSION['user_role']) ||
            $_SESSION['user_role'] === 'Usuario'
        ) {
            header("Location: index.php?action=login");
            exit;
        }

        $name = $_POST['name'] ?? '';
        $price = (float) ($_POST['price'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);
        $categoryId = $_POST['category_id'] ?? null;

        $useCase = new AddProductUseCase($this->productRepo);

        $useCase->execute(
            $name,
            $price,
            $stock,
            $categoryId
        );

        $_SESSION['message'] = "Producto agregado correctamente.";

        header("Location: index.php?action=list");
        exit;
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
            $this->productRepo->delete((int)$id);
        }

        header("Location: index.php?action=list");
        exit;
    }

    public function addToCart()
    {
        if (
            !isset($_SESSION['user_role']) ||
            $_SESSION['user_role'] !== 'Usuario'
        ) {
            die("Solo clientes");
        }

        $productId = $_GET['id'] ?? null;

        if (!$productId) {
            header("Location: index.php?action=list");
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $_SESSION['cart'][] = $productId;

        header("Location: index.php?action=list");
        exit;
    }

    public function viewCart()
    {
        include '../src/Presentation/views/cart.php';
    }
}