<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra Exitosa - Sistema Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <style>
        .success-card {
            max-width: 700px;
            margin: 40px auto;
            background: #1e2937;
            border: 3px solid #22c55e;
            border-radius: 20px;
            padding: 40px;
        }
        .checkmark {
            font-size: 5rem;
            color: #22c55e;
        }
    </style>
</head>
<body class="container" style="background:#0f172a; color:#e2e8f0;">

    <nav style="background:#1e2937;">
        <ul><li><strong>✅ Compra Exitosa</strong></li></ul>
        <ul>
            <li><a href="index.php?action=list">Volver al Inventario</a></li>
            <li><a href="index.php?action=my_orders">Ver Todas mis Compras</a></li>
        </ul>
    </nav>

    <main>
        <div class="success-card">
            <div style="text-align:center;">
                <div class="checkmark">✅</div>
                <h1>¡Gracias por tu compra!</h1>
                <p style="font-size:1.3rem; color:#86efac;">Tu pedido ha sido procesado correctamente.</p>
            </div>

            <?php if (!empty($orderDetails)): ?>
                <hr>
                <h2>Comprobante #<?= $orderDetails['id'] ?></h2>
                <p><strong>Fecha:</strong> <?= $orderDetails['created_at'] ?></p>
                <p><strong>Cliente:</strong> <?= htmlspecialchars($orderDetails['username']) ?></p>

                <table role="grid" style="margin:25px 0;">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderDetails['items'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><strong>$<?= number_format($item['price'] * $item['quantity'], 2) ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="text-align:right; font-size:1.5rem; margin-top:20px;">
                    <strong>Total Pagado: $<?= number_format($orderDetails['total'], 2) ?></strong>
                </div>
    
                <a href="index.php?action=download_pdf&id=<?= $orderDetails['id'] ?>" class="contrast">
                    ⬇️ Descargar Comprobante PDF
                </a>
                
            <?php endif; ?>

            <div style="text-align:center; margin-top:40px;">
                <a href="index.php?action=list" class="contrast" style="padding:15px 40px; font-size:1.2rem;">
                    Volver al Inventario
                </a>
            </div>
        </div>
    </main>
</body>
</html>