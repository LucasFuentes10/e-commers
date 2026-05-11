<main class="container">
    <h2>Gestión de Personal</h2>
    <table role="grid">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><mark><?= $u['role_name'] ?></mark></td>
                    <td>
                        <?php if ($u['role_name'] !== 'Admin'): ?>
                            <a href="?action=delete_user&id=<?= $u['id'] ?>" 
                               onclick="return confirm('¿Eliminar acceso a este empleado?')" 
                               style="color:red;">Dar de Baja</a>
                        <?php else: ?>
                            <span>-</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="?action=list">Volver al Inventario</a>
</main>