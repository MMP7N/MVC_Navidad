<?php require __DIR__ . '/header.php'; ?>

<h2>Registro de usuario</h2>

<?php if (!empty($errores)): ?>
    <ul>
        <?php foreach ($errores as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST">
    <label>Usuario: <input type="text" name="user" required></label><br>
    <label>Nombre: <input type="text" name="nombre" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <label>Contraseña: <input type="password" name="password" required></label><br>
    <label>Rol:
        <select name="rol">
            <option value="padre">Padre/Madre</option>
            <option value="papanoel">Papá Noel</option>
        </select>
    </label><br>
    <button type="submit">Registrarse</button>
</form>

<?php require __DIR__ . '/footer.php'; ?>
