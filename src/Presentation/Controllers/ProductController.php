<?php
// src/Presentation/Controllers/ProductController.php

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

        include __DIR__ . '/../Views/product_list.php';
    }

    public function addForm()
    {
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['Admin', 'Empleado'])) {
            die("No autorizado");
        }

        $categories = $this->categoryRepo->getAll();
        $suppliers  = $this->productRepo->getAllSuppliers();   // ← Nuevo método

        include __DIR__ . '/../Views/add_product.php';
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

        $name        = trim($_POST['name'] ?? '');
        $price       = (float) ($_POST['price'] ?? 0);
        $stock       = (int) ($_POST['stock'] ?? 0);
        $categoryId  = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $supplierId  = !empty($_POST['supplier_id']) ? (int)$_POST['supplier_id'] : null;
        $description = trim($_POST['description'] ?? '');

        $useCase = new AddProductUseCase($this->productRepo);

        $useCase->execute(
            $name, 
            $price, 
            $stock, 
            $categoryId, 
            $supplierId, 
            $description
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

    // ==================== CARRITO ====================
    public function addToCart()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Usuario') {
            die("Solo clientes pueden usar el carrito");
        }

        $productId = (int)($_GET['id'] ?? 0);

        if ($productId > 0) {
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
            $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + 1;
        }

        header("Location: index.php?action=list");
        exit;
    }

    public function removeFromCart()
    {
        $productId = (int)($_GET['id'] ?? 0);
        if ($productId > 0 && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
        header("Location: index.php?action=view_cart");
        exit;
    }

    public function viewCart()
    {
        $cart = $_SESSION['cart'] ?? [];
        $products = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $this->productRepo->findById((int)$productId);
            if ($product) {
                $product['quantity'] = $quantity;
                $product['subtotal'] = $product['price'] * $quantity;
                $products[] = $product;
                $total += $product['subtotal'];
            }
        }

        include __DIR__ . '/../Views/cart.php';
    }

    public function checkout()
    {
        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = "El carrito está vacío.";
            header("Location: index.php?action=view_cart");
            exit;
        }

        try {
            $total = 0;
            $items = [];

            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $product = $this->productRepo->findById((int)$productId);
                if (!$product || $product['stock'] < $quantity) {
                    throw new \Exception("Stock insuficiente o producto no encontrado.");
                }

                $subtotal = $product['price'] * $quantity;
                $total += $subtotal;

                $items[] = [
                    'product_id' => $productId,
                    'quantity'   => $quantity,
                    'price'      => $product['price']
                ];

                $this->productRepo->updateStock($productId, $product['stock'] - $quantity);
                $this->productRepo->registerStockMovement($productId, $quantity, 'salida');
            }

            $orderId = $this->productRepo->createOrder($_SESSION['user_id'], $total, $items);

            unset($_SESSION['cart']);
            
            // Simulación de pasarela de pago
            $_SESSION['message'] = "¡Pago procesado con éxito! Orden #$orderId generada correctamente.";
            
            header("Location: index.php?action=order_success&id=$orderId");

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: index.php?action=view_cart");
        }
        exit;
    }

    public function myOrders()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $orders = $this->productRepo->getUserOrders($_SESSION['user_id']);
        include __DIR__ . '/../Views/my_orders.php';
    }
    public function updateCartQuantity()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)($_POST['product_id'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? 1);

            if ($productId > 0) {
                if ($quantity > 0) {
                    $_SESSION['cart'][$productId] = $quantity;
                } else {
                    unset($_SESSION['cart'][$productId]);
                }
            }
        }

        header("Location: index.php?action=view_cart");
        exit;
    }

    

    // ==================== MÉTODOS DE PROVEEDORES ====================

    public function listSuppliers()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
            die("No autorizado");
        }

        $suppliers = $this->productRepo->getAllSuppliers();
        include __DIR__ . '/../Views/suppliers_list.php';
    }

    public function addSupplierForm()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
            die("No autorizado");
        }
        include __DIR__ . '/../Views/add_supplier.php';
    }

    public function saveSupplier()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
            die("No autorizado");
        }

        $name    = trim($_POST['name'] ?? '');
        $contact = trim($_POST['contact'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $email   = trim($_POST['email'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = "El nombre del proveedor es obligatorio.";
            header("Location: index.php?action=add_supplier_form");
            exit;
        }

        $this->productRepo->saveSupplier($name, $contact, $phone, $email);

        $_SESSION['message'] = "Proveedor agregado correctamente.";
        header("Location: index.php?action=list_suppliers");
        exit;
    }

    public function deleteSupplier()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
            die("No autorizado");
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->productRepo->deleteSupplier($id);
            $_SESSION['message'] = "Proveedor eliminado.";
        }

        header("Location: index.php?action=list_suppliers");
        exit;
    }

    public function orderSuccess()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $orderId = (int)($_GET['id'] ?? 0);

        // Obtener los detalles de la orden
        $orderDetails = $this->productRepo->getOrderDetails($orderId);

        include __DIR__ . '/../Views/order_success.php';
    }

    public function downloadPDF()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $orderId = (int)($_GET['id'] ?? 0);

        if ($orderId <= 0) {
            $_SESSION['error'] = "Orden inválida.";
            header("Location: index.php?action=my_orders");
            exit;
        }

        try {
            $this->productRepo->generateOrderPDF($orderId);
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error al generar el PDF: " . $e->getMessage();
            header("Location: index.php?action=order_success&id=" . $orderId);
        }
        exit;
    }
}