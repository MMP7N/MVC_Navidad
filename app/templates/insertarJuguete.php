<?php require __DIR__ . '/header.php'; ?>

<h2>Insertar nuevo juguete</h2>

<?php if (!empty($errores)): ?>
    <ul style="color:red;">
        <?php foreach ($errores as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST">
    <label>Nombre:
        <input type="text" name="nombre" required>
    </label><br>

    <label>Descripción:
        <textarea name="descripcion"></textarea>
    </label><br>

    <label>Precio (€):
        <input type="number" step="0.01" name="precio" required>
    </label><br>

    <button type="submit">Guardar Juguete</button>
</form>

<?php require __DIR__ . '/footer.php'; ?>
