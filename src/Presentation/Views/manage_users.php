<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Personal - Sistema Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <style>
        .role-admin { background: #e0f2fe; color: #0369a1; }
        .role-empleado { background: #fef3c7; color: #92400e; }
        .role-usuario { background: #f3e8ff; color: #6b21a8; }
        
        .user-card {
            transition: all 0.2s ease;
        }
        .user-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }
    </style>
</head>
<body class="container">

    <nav>
        <ul>
            <li><strong>👥 Gestión de Personal</strong></li>
        </ul>
        <ul>
            
            <li><a href="index.php?action=list">← Volver al Inventario</a></li>
            <?php if ($_SESSION['user_role'] === 'Admin'): ?>
                <li><a href="index.php?action=admin_add_user" class="contrast">+ Agregar Nuevo Usuario</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main>
        <article>
            <header>
                <h2>Gestión de Staff</h2>
                <p>Administración de usuarios del sistema</p>
            </header>

            <?php if (isset($_SESSION['message'])): ?>
                <mark><?= htmlspecialchars($_SESSION['message']) ?></mark>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <table role="grid" class="user-card">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($u['username']) ?></strong>
                            </td>
                            <td>
                                <?= htmlspecialchars($u['email']) ?>
                            </td>
                            <td>
                                <?php 
                                $roleClass = '';
                                if ($u['role_name'] === 'Admin') $roleClass = 'role-admin';
                                elseif ($u['role_name'] === 'Empleado') $roleClass = 'role-empleado';
                                else $roleClass = 'role-usuario';
                                ?>
                                <span class="badge <?= $roleClass ?>">
                                    <?= htmlspecialchars($u['role_name']) ?>
                                </span>
                            </td>
                            <td>
                                <span style="color: #22c55e; font-weight: bold;">● Activo</span>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($u['role_name'] !== 'Admin'): ?>
                                    <a href="index.php?action=delete_user&id=<?= $u['id'] ?>" 
                                       onclick="return confirm('¿Estás seguro de eliminar al usuario <?= htmlspecialchars($u['username']) ?>?\n\nEsta acción no se puede deshacer.')"
                                       class="secondary outline"
                                       style="color: #ef4444; border-color: #ef4444;">
                                        Dar de Baja
                                    </a>
                                <?php else: ?>
                                    <em style="color: #64748b;">Protegido</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (empty($users)): ?>
                <p style="text-align: center; padding: 40px; color: #64748b;">
                    No hay usuarios registrados.
                </p>
            <?php endif; ?>
        </article>
    </main>

</body>
</html>