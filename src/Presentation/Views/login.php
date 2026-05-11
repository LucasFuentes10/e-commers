<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Mercadería</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body class="container">
    <article style="max-width: 400px; margin: 50px auto;">
        <h2>Identificarse</h2>
        <form action="index.php?action=do_login" method="POST">
            <label>Email</label>
            <input type="email" name="email" required>
            
            <label>Contraseña</label>
            <input type="password" name="password" required>
            
            <button type="submit">Entrar al Sistema</button>
        </form>
        <?php if(isset($error)): ?>
            <small style="color: red;"><?php echo $error; ?></small>
        <?php endif; ?>
        
        <footer style="margin-top: 15px; text-align: center;">
            ¿No tienes cuenta? <a href="index.php?action=register">Regístrate aquí</a>
        </footer>
        
    </article>
</body>
</html>