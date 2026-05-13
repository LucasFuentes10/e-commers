<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Proveedor - Sistema Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body class="container">

    <nav>
        <ul><li><strong>👥 Nuevo Proveedor</strong></li></ul>
        <ul>
            <li><a href="index.php?action=list_suppliers">← Volver a Proveedores</a></li>
        </ul>
    </nav>

    <main>
        <article style="max-width: 500px; margin: 40px auto;">
            <header><h2>Agregar Proveedor</h2></header>
            
            <form action="index.php?action=do_save_supplier" method="POST">
                <label>Nombre del Proveedor</label>
                <input type="text" name="name" required>

                <label>Contacto (Nombre)</label>
                <input type="text" name="contact">

                <label>Teléfono</label>
                <input type="text" name="phone">

                <label>Email</label>
                <input type="email" name="email">

                <button type="submit" class="contrast">Guardar Proveedor</button>
            </form>
        </article>
    </main>
</body>
</html>