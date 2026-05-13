<!DOCTYPE html>
<html lang="es">
    <!-- src/Presentation/Views/register.php -->
<head>
    <meta charset="UTF-8">
    <title>Registro - Sistema de Mercadería</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<?php if (isset($error)): ?>
    <article style="background: #f8d7da; color: #721c24; padding: 10px;">
        ⚠️ <?php echo $error; ?>
    </article>
<?php endif; ?>
<body class="container">
    <article style="max-width: 500px; margin: 50px auto;">
        <h2>Crear Nueva Cuenta</h2>
        <form action="index.php?action=do_register" method="POST">
            <label>Nombre de Usuario</label>
            <input type="text" name="username" placeholder="Ej: juan_perez" required>

            <label>Correo Electrónico</label>
            <input type="email" name="email" placeholder="correo@ejemplo.com" required>
            
            <label>Contraseña</label>
            <input type="password" name="password" required>
            
            <button type="submit">Registrarse</button>
        </form>
        <p align="center"><a href="index.php?action=login">¿Ya tienes cuenta? Inicia sesión</a></p>
    </article>
</body>
</html>