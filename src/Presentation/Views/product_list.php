<!-- src/Presentation/Views/product_list.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - Sistema Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <style>
        .product-row:hover { background: #1e2937; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; }
        .modal-content { background: #1e2937; max-width: 600px; margin: 100px auto; padding: 30px; border-radius: 16px; }
    </style>
</head>
<body class="container" style="background:#0f172a; color:#e2e8f0;">

    <!-- NAV (mantengo el que tenías) -->
    <nav style="background:#1e2937;">
        <ul><li><strong>📦 Sistema Pro</strong></li></ul>
        <ul>
            <?php if ($_SESSION['user_role'] === 'Admin' || $_SESSION['user_role'] === 'Empleado'): ?>
                <li><a href="index.php?action=add_form" class="contrast">+ Nueva Mercadería</a></li>
            <?php endif; ?>

            <?php if ($_SESSION['user_role'] === 'Admin'): ?>
                <li><a href="index.php?action=manage_users">👥 Staff</a></li>
                <li><a href="index.php?action=list_suppliers">🏭 Proveedores</a></li>
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

            <table role="grid">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr class="product-row">
                        <td>
                            <a href="#" onclick="showDescription('<?= htmlspecialchars($p['name']) ?>', '<?= htmlspecialchars($p['description'] ?? 'Sin descripción') ?>')" 
                               style="color:#22d3ee; text-decoration:underline;">
                                <?= htmlspecialchars($p['name']) ?>
                            </a>
                        </td>
                        <td><strong>$<?= number_format($p['price'], 2) ?></strong></td>
                        <td>
                            <?= $p['stock'] ?>
                            <?php if ($p['stock'] <= 5): ?>
                                <span style="color:#ef4444;">(Bajo)</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['category_name'] ?? 'Sin categoría') ?></td>
                        <td><?= htmlspecialchars($p['supplier_name'] ?? 'Sin proveedor') ?></td>
                        <td style="text-align: center;">
                            <?php if ($_SESSION['user_role'] === 'Usuario'): ?>
                                <a href="index.php?action=add_to_cart&id=<?= $p['id'] ?>" class="contrast">🛒 Añadir</a>
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
        </article>
    </main>

    <!-- MODAL DE DESCRIPCIÓN -->
    <div id="descriptionModal" class="modal">
        <div class="modal-content">
            <h3 id="modalProductName"></h3>
            <p id="modalProductDesc"></p>
            <button onclick="closeModal()" class="contrast">Cerrar</button>
        </div>
    </div>

    <script>
        function showDescription(name, description) {
            document.getElementById('modalProductName').textContent = name;
            document.getElementById('modalProductDesc').innerHTML = description || '<em style="color:#94a3b8;">Sin descripción disponible.</em>';
            document.getElementById('descriptionModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('descriptionModal').style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('descriptionModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>