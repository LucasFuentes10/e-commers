<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Mercadería</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body class="container">
    <nav>
        <ul>
            <li><strong>📦 Sistema Pro</strong></li>
        </ul>
        <ul>
            <li><a href="?action=list">Ver Inventario</a></li>
        
                <?php if ($_SESSION['user_role'] === 'Admin' || $_SESSION['user_role'] === 'Empleado'): ?>
                    <li><a href="?action=add_form" role="button" class="secondary">Nueva Mercadería</a></li>
                <?php endif; ?>

                <?php if ($_SESSION['user_role'] === 'Admin'): ?>
                    <li><a href="?action=manage_users" role="button" class="outline">Gestionar Staff</a></li>
                <?php endif; ?>
            
            <li><a href="?action=logout" style="color:red;">Salir</a></li>
        </ul>
    </nav>

    <?php if (isset($_SESSION['message'])): ?>
        <mark><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></mark>
    <?php endif; ?>

    <main>
        <section>
            <h2>Agregar Nueva Mercadería</h2>
            <form action="?action=save_product" method="POST">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ??= bin2hex(random_bytes(32)); ?>">
                <div class="grid">
                    <input type="text" name="name" placeholder="Nombre del producto" required>
                    <input type="number" name="price" placeholder="Precio" step="0.01" required>
                    <input type="number" name="stock" placeholder="Stock inicial" required>
                </div>
                <button type="submit">Guardar Producto</button>
            </form>
        </section>

        <section>
            <h2>Inventario Actual</h2>
           <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= $p['name'] ?></td>
                        <td>$<?= $p['price'] ?></td>
                        <td>
                            <?php if ($_SESSION['user_role'] === 'Usuario'): ?>
                                <a href="?action=add_to_cart&id=<?= $p['id'] ?>" role="button" class="contrast">🛒 Añadir</a>
                            <?php endif; ?>

                            <?php if ($_SESSION['user_role'] === 'Admin'): ?>
                                <a href="?action=delete_product&id=<?= $p['id'] ?>" style="color:red;">Eliminar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>