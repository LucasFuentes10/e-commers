<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Compras - Sistema Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <style>
        .order-card {
            transition: all 0.3s ease;
        }
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body class="container" style="background:#0f172a; color:#e2e8f0;">

    <nav style="background:#1e2937;">
        <ul><li><strong>🛍️ Mis Compras</strong></li></ul>
        <ul>
            <li><a href="index.php?action=list">← Inventario</a></li>
        </ul>
    </nav>

    <main>
        <h2>Mis Compras Realizadas</h2>

        <?php if (empty($orders)): ?>
            <p style="text-align:center; padding:60px;">Aún no has realizado ninguna compra.</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <article class="order-card" style="background:#1e2937; padding:20px; border-radius:12px; margin-bottom:20px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <h3>Orden #<?= $order['id'] ?></h3>
                            <p>
                                <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?><br>
                                <strong>Total:</strong> <span style="color:#22d3ee; font-size:1.3rem;">$<?= number_format($order['total'], 2) ?></span><br>
                                <strong>Productos:</strong> <?= $order['total_items'] ?>
                            </p>
                        </div>
                        <div style="text-align:right;">
                            <a href="index.php?action=download_pdf&id=<?= $order['id'] ?>" 
                               class="contrast" style="padding:10px 20px;">
                                ⬇️ Descargar PDF
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>