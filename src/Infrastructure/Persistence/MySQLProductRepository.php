<?php
// src/Infrastructure/Persistence/MySQLProductRepository.php

namespace App\Infrastructure\Persistence;

use App\Infrastructure\Database;
use App\Domain\Entities\Product;
use App\Domain\Interfaces\ProductRepositoryInterface;
use PDO;
// Verifica que diga "c.name" y que la tabla sea "categories c"
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.id DESC";
class MySQLProductRepository implements ProductRepositoryInterface {
    private $pdo;

    public function __construct() {
        // Obtenemos la conexión única a través de nuestra clase Database
        $this->pdo = Database::getConnection();
    }

    // src/Infrastructure/Persistence/MySQLProductRepository.php

    public function getAll(): array
    {
        $sql = "SELECT p.*, 
                    c.name as category_name, 
                    s.name as supplier_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN suppliers s ON p.supplier_id = s.id 
                ORDER BY p.id DESC";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(Product $product): bool
    {
        $sql = "INSERT INTO products (name, price, stock, category_id, supplier_id, description) 
                VALUES (:name, :price, :stock, :category_id, :supplier_id, :description)";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            'name'        => $product->name,
            'price'       => $product->price,
            'stock'       => $product->stock,
            'category_id' => $product->categoryId,
            'supplier_id' => $product->supplierId ?? null,
            'description' => $product->description ?? null
        ]);
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT p.*, c.name as category_name, s.name as supplier_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN suppliers s ON p.supplier_id = s.id 
                WHERE p.id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    /**
     * Elimina un producto (Solo si el usuario tiene permiso, validado en el controlador)
     */
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Actualiza el stock de un producto (Para la tabla de movimientos)
     */
    public function updateStock(int $id, int $newStock): bool {
        $stmt = $this->pdo->prepare("UPDATE products SET stock = :stock WHERE id = :id");
        return $stmt->execute(['stock' => $newStock, 'id' => $id]);
    }

    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    public function commit()
    {
        $this->pdo->commit();
    }

    public function rollback()
    {
        $this->pdo->rollBack();
    }

    public function registerStockMovement(int $productId, int $quantity, string $type): bool
    {
        $sql = "INSERT INTO stock_movements (product_id, quantity, type) VALUES (:pid, :qty, :type)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'pid'  => $productId,
            'qty'  => $quantity,
            'type' => $type
        ]);
    }

    public function getAllSuppliers(): array
    {
        $sql = "SELECT * FROM suppliers ORDER BY name ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveSupplier(string $name, string $contact = '', string $phone = '', string $email = ''): bool
    {
        $sql = "INSERT INTO suppliers (name, contact, phone, email) 
                VALUES (:name, :contact, :phone, :email)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name'    => $name,
            'contact' => $contact,
            'phone'   => $phone,
            'email'   => $email
        ]);
    }

    public function deleteSupplier(int $id): bool
    {
        // Evitar eliminar si tiene productos asociados
        $check = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE supplier_id = :id");
        $check->execute(['id' => $id]);
        
        if ($check->fetchColumn() > 0) {
            $_SESSION['error'] = "No se puede eliminar: Tiene productos asociados.";
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM suppliers WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function createOrder(int $userId, float $total, array $items): int
    {
        $this->pdo->beginTransaction();

        try {
            // Crear la orden
            $sql = "INSERT INTO orders (user_id, total) VALUES (:user_id, :total)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'user_id' => $userId,
                'total'   => $total
            ]);
            
            $orderId = $this->pdo->lastInsertId();

            // Insertar los items
            $sqlItem = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                        VALUES (:order_id, :product_id, :quantity, :price)";
            $stmtItem = $this->pdo->prepare($sqlItem);

            foreach ($items as $item) {
                $stmtItem->execute([
                    'order_id'   => $orderId,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price']
                ]);
            }

            $this->pdo->commit();
            return $orderId;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function getUserOrders(int $userId): array
    {
        $sql = "SELECT o.id, o.total, o.created_at, o.status,
                    COUNT(oi.id) as total_items
                FROM orders o 
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.user_id = :user_id 
                GROUP BY o.id 
                ORDER BY o.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getOrderDetails(int $orderId): array
    {
        // Info de la orden
        $sql = "SELECT o.*, u.username 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.id = :order_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) return [];

        // Productos de la orden
        $sqlItems = "SELECT oi.*, p.name 
                    FROM order_items oi 
                    JOIN products p ON oi.product_id = p.id 
                    WHERE oi.order_id = :order_id";
        
        $stmtItems = $this->pdo->prepare($sqlItems);
        $stmtItems->execute(['order_id' => $orderId]);
        $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }

    public function generateOrderPDF(int $orderId)
    {
        $order = $this->getOrderDetails($orderId);

        if (empty($order)) {
            throw new \Exception("Orden no encontrada.");
        }

        // Cargar Dompdf
        require_once __DIR__ . '/../../../vendor/autoload.php';
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->setPaper('A4', 'portrait');

        $html = '
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Comprobante #' . $orderId . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                h1 { text-align: center; color: #22c55e; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 4px solid #22c55e; padding-bottom: 15px; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { border: 1px solid #333; padding: 12px; text-align: left; }
                th { background-color: #1e2937; color: white; }
                .total { font-size: 1.6em; text-align: right; font-weight: bold; margin-top: 30px; color: #22c55e; }
                .footer { text-align: center; margin-top: 50px; color: #666; font-size: 0.9em; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>:) Sistema Pro (:</h1>
                <h2>Comprobante de Compra</h2>
            </div>

            <p><strong>Orden N°:</strong> #' . $order['id'] . '</p>
            <p><strong>Fecha:</strong> ' . date('d/m/Y H:i', strtotime($order['created_at'])) . '</p>
            <p><strong>Cliente:</strong> ' . htmlspecialchars($order['username']) . '</p>
            <hr>

            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($order['items'] as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $html .= '
                    <tr>
                        <td>' . htmlspecialchars($item['name']) . '</td>
                        <td style="text-align:center;">' . $item['quantity'] . '</td>
                        <td style="text-align:right;">$' . number_format($item['price'], 2) . '</td>
                        <td style="text-align:right;">$' . number_format($subtotal, 2) . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="total">
                TOTAL PAGADO: $' . number_format($order['total'], 2) . '
            </div>

            <div class="footer">
                Gracias por tu compra • Sistema Pro<br>
                Comprobante generado el ' . date('d/m/Y H:i') . '
            </div>
        </body>
        </html>';

        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("Comprobante_Orden_" . $orderId . ".pdf", array("Attachment" => true));
        exit;
    }
}