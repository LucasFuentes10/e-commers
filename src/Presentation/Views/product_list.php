<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - Sistema Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <style>
        .product-card {
            transition: all 0.2s ease;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }
        .low-stock { color: #ef4444; font-weight: bold; }
    </style>
</head>
<body class="container">

    <nav>
        <ul>
            <li><strong>📦 Sistema Pro</strong></li>
        </ul>
        <ul>
            <?php if ($_SESSION['user_role'] === 'Admin' || $_SESSION['user_role'] === 'Empleado'): ?>
                <li><a href="index.php?action=add_form" role="button" class="secondary">+ Nueva Mercadería</a></li>
            <?php endif; ?>

            <?php if ($_SESSION['user_role'] === 'Admin'): ?>
                <li><a href="index.php?action=manage_users" role="button" class="outline">👥 Gestionar Staff</a></li>
                <li><a href="index.php?action=list_suppliers" role="button" class="outline">🏭 Proveedores</a></li>
            <?php endif; ?>
            <?php if ($_SESSION['user_role'] === 'Usuario'): ?>
                <li><a href="index.php?action=view_cart">🛒 Mi Carrito</a></li>
            <?php endif; ?>
            <li><a href="index.php?action=my_orders">🛍️ Mis Compras</a></li>
            <li><a href="index.php?action=logout" style="color:#ef4444;">Salir</a></li>
        </ul>
    </nav>

    <main>
        <?php if (isset($_SESSION['message'])): ?>
            <mark><?= htmlspecialchars($_SESSION['message']) ?></mark>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <article>
            <header>
                <h2>Inventario Actual</h2>
                <p><?= count($products) ?> productos registrados</p>
            </header>

            <table role="grid" class="product-card">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Categoría</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
                        <td><strong>$<?= number_format($p['price'], 2) ?></strong></td>
                        <td>
                            <?= $p['stock'] ?>
                            <?php if ($p['stock'] <= 5): ?>
                                <span class="low-stock">(Bajo)</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['category_name'] ?? 'Sin categoría') ?></td>
                        <td style="text-align: center;">
                            <?php if ($_SESSION['user_role'] === 'Usuario'): ?>
                                <a href="index.php?action=add_to_cart&id=<?= $p['id'] ?>" 
                                   class="contrast">🛒 Añadir</a>
                            <?php endif; ?>

                            <?php if ($_SESSION['user_role'] === 'Admin'): ?>
                                <a href="index.php?action=delete_product&id=<?= $p['id'] ?>" 
                                   onclick="return confirm('¿Eliminar <?= htmlspecialchars($p['name']) ?>?')"
                                   style="color:#ef4444;">Eliminar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (empty($products)): ?>
                <p style="text-align:center; padding:60px; color:#64748b;">
                    No hay productos en el inventario.
                </p>
            <?php endif; ?>
        </article>
    </main>
</body>
</html>