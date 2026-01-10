<?php require __DIR__ . '/header.php'; ?>

<h2>Crear un nuevo niño</h2>

<?php if (!empty($errores)): ?>
    <ul style="color:red;">
        <?php foreach ($errores as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST">
    <label>Usuario: <input type="text" name="user" required></label><br>
    <label>Contraseña: <input type="password" name="password" required></label><br>
    <label>Nombre: <input type="text" name="nombre" required></label><br>
    <label>Edad: <input type="number" name="edad" required min="1" max="18"></label><br>
    <button type="submit">Crear Niño</button>
</form>

<?php require __DIR__ . '/footer.php'; ?>
