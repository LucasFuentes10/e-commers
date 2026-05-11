<main class="container">
    <article>
        <h2>Cargar Nueva Mercadería</h2>
        <form action="?action=save_product" method="POST">
            <label>Nombre del Producto</label>
            <input type="text" name="name" required>
            
            <div class="grid">
                <label>Precio
                    <input type="number" step="0.01" name="price" required>
                </label>
                <label>Stock Inicial
                    <input type="number" name="stock" required>
                </label>
            </div>

            <label>Categoría</label>
            <select name="category_id">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Guardar en Base de Datos</button>
            <a href="?action=list" class="secondary">Cancelar</a>
        </form>
    </article>
</main>