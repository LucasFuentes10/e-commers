<!-- src/Presentation/Views/add_product.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto - Sistema Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body class="container">

    <nav>
        <ul><li><strong>📦 Sistema Pro</strong></li></ul>
        <ul>
            <li><a href="index.php?action=list">← Volver al Inventario</a></li>
        </ul>
    </nav>

    <main>
        <article style="max-width: 700px; margin: 40px auto;">
            <header>
                <h2>Agregar Nueva Mercadería</h2>
            </header>

            <form action="index.php?action=do_save_product" method="POST">
                <label>Nombre del Producto</label>
                <input type="text" name="name" required>

                <label>Descripción</label>
                <textarea name="description" rows="3" placeholder="Descripción opcional..."></textarea>

                <div class="grid">
                    <div>
                        <label>Precio ($)</label>
                        <input type="number" name="price" step="0.01" min="0" required>
                    </div>
                    <div>
                        <label>Stock Inicial</label>
                        <input type="number" name="stock" min="0" required>
                    </div>
                </div>

                <div class="grid">
                    <div>
                        <label>Categoría</label>
                        <select name="category_id" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label>Proveedor</label>
                        <select name="supplier_id">
                            <option value="">Sin proveedor</option>
                            <?php foreach ($suppliers as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="contrast">Guardar Producto</button>
            </form>
        </article>
    </main>
</body>
</html>