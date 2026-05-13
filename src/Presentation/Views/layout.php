<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Sistema' ?></title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>

<body class="container">

<nav>
    <ul>
        <li><strong>📦 Sistema Pro</strong></li>
    </ul>

    <ul>

        <?php if(isset($_SESSION['user_id'])): ?>

            <li>
                <a href="index.php?action=list">
                    Inventario
                </a>
            </li>

            <?php if ($_SESSION['user_role'] === 'Usuario'): ?>

                <li>
                    <a href="index.php?action=view_cart">
                        Mi Carrito
                    </a>
                </li>

            <?php endif; ?>

            <li>
                <a href="index.php?action=logout">
                    Salir
                </a>
            </li>

        <?php endif; ?>

    </ul>
</nav>