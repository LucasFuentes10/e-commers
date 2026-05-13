<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body class="container">

    <nav>
        <ul><li><strong>👤 Agregar Nuevo Usuario</strong></li></ul>
        <ul>
            <li><a href="index.php?action=manage_users">← Volver a Gestión de Staff</a></li>
        </ul>
    </nav>

    <main>
        <article style="max-width: 500px; margin: 40px auto;">
            <header><h2>Crear Nuevo Usuario</h2></header>

            <?php if (isset($_SESSION['error'])): ?>
                <div style="background:#fee2e2; color:#b91c1c; padding:15px; border-radius:8px;">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="index.php?action=admin_do_add_user" method="POST">
                <label>Nombre de Usuario</label>
                <input type="text" name="username" required>

                <label>Correo Electrónico</label>
                <input type="email" name="email" required>

                <label>Contraseña</label>
                <input type="password" name="password" required minlength="6">

                <label>Rol</label>
                <select name="role_id" required>
                    <option value="1">Admin</option>
                    <option value="2" selected>Empleado</option>
                    <option value="3">Usuario (Cliente)</option>
                </select>

                <button type="submit" class="contrast">Crear Usuario</button>
            </form>
        </article>
    </main>
</body>
</html>