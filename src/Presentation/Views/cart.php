<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Carrito - Sistema Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <style>
        .cart-item {
            transition: all 0.3s ease;
        }
        .cart-item:hover {
            transform: translateX(8px);
            background: #1e2937;
        }
        .total-card {
            background: #0f172a;
            border: 2px solid #22d3ee;
            border-radius: 16px;
        }
    </style>
</head>
<body class="container" style="background: #0f172a; color: #e2e8f0;">

    <nav style="background: #1e2937; border-bottom: 3px solid #22d3ee;">
        <ul>
            <li><strong>🛒 Mi Carrito</strong></li>
        </ul>
        <ul>
            <li><a href="index.php?action=list">← Seguir Comprando</a></li>
            <li><a href="index.php?action=my_orders">🛍️ Mis Compras</a></li>
        </ul>
    </nav>

    <main>
        <?php if (isset($_SESSION['error'])): ?>
            <div style="background:#b91c1c; color:white; padding:15px; border-radius:12px; margin:20px 0;">
                ⚠️ <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($products)): ?>
            <div style="text-align:center; padding:100px 20px;">
                <h1 style="font-size:4rem; opacity:0.3;">🛒</h1>
                <h2>Tu carrito está vacío</h2>
                <a href="index.php?action=list" class="contrast" style="font-size:1.2rem; padding:15px 40px;">
                    Ver Productos Disponibles
                </a>
            </div>
        <?php else: ?>
            <h2 style="margin-bottom: 30px;">Productos en tu Carrito</h2>

            <?php foreach ($products as $p): ?>
            <article class="cart-item" style="margin-bottom:15px; padding:15px; border-radius:12px; background:#1e2937;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <strong style="font-size:1.3rem;"><?= htmlspecialchars($p['name']) ?></strong><br>
                        <small style="color:#94a3b8;">$<?= number_format($p['price'], 2) ?> c/u</small>
                    </div>
                    <div style="text-align:right;">
                        <h3 style="margin:0; color:#22d3ee;">$<?= number_format($p['subtotal'], 2) ?></h3>
                        <small>Cantidad: <strong><?= $p['quantity'] ?></strong></small>
                    </div>
                    <a href="index.php?action=remove_from_cart&id=<?= $p['id'] ?>" 
                       onclick="return confirm('¿Eliminar este producto?')"
                       style="color:#ef4444; font-size:1.5rem; text-decoration:none;">✕</a>
                </div>
            </article>
            <?php endforeach; ?>

            <!-- Total Card -->
            <div class="total-card" style="padding:30px; margin-top:40px; text-align:center;">
                <h2>Total a Pagar</h2>
                <h1 style="font-size:3rem; color:#22d3ee; margin:10px 0;">
                    $<?= number_format($total, 2) ?>
                </h1>
                
                <a href="index.php?action=checkout" 
                   onclick="return confirm('¿Confirmar la compra y procesar el pago?')"
                   style="font-size:1.3rem; padding:18px 50px; background:#22d3ee; color:#0f172a; border-radius:12px; text-decoration:none; font-weight:bold;">
                    💳 Finalizar Compra y Pagar
                </a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>