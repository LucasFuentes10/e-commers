<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proveedores - Sistema Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body class="container">

    <nav>
        <ul><li><strong>👥 Proveedores</strong></li></ul>
        <ul>
            <li><a href="index.php?action=list">← Inventario</a></li>
            <li><a href="index.php?action=add_supplier_form" class="contrast">+ Nuevo Proveedor</a></li>
        </ul>
    </nav>

    <main>
        <?php if (isset($_SESSION['message'])): ?>
            <mark><?= htmlspecialchars($_SESSION['message']) ?></mark>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div style="background:#fee2e2;color:#b91c1c;padding:12px;border-radius:8px;">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <table role="grid">
            <thead>
                <tr>
                    <th>Proveedor</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suppliers as $s): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($s['name']) ?></strong></td>
                    <td><?= htmlspecialchars($s['contact'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($s['phone'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($s['email'] ?? '-') ?></td>
                    <td>
                        <a href="index.php?action=delete_supplier&id=<?= $s['id'] ?>" 
                           onclick="return confirm('¿Eliminar proveedor?')"
                           style="color:#ef4444;">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>