<?php require __DIR__ . '/header.php'; ?>

<h2>Login</h2>

<?php if (!empty($errores['general'])): ?>
    <p style="color:red;"><?= htmlspecialchars($errores['general']) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Usuario: <input type="text" name="user" required></label><br>
    <label>Contraseña: <input type="password" name="password" required></label><br>
    <button type="submit">Entrar</button>
</form>

<?php require __DIR__ . '/footer.php'; ?>
