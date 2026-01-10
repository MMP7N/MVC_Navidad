<?php require __DIR__ . '/header.php'; ?>

<h2>Crear tu carta</h2>

<form method="POST">
    <?php foreach ($juguetes as $j): ?>
        <label>
            <input type="checkbox" name="juguetes[]" value="<?= $j['id'] ?>">
            <?= htmlspecialchars($j['nombre']) ?> (<?= $j['precio'] ?> €)
        </label><br>
    <?php endforeach; ?>

    <button type="submit">Guardar Carta</button>
</form>

<?php require __DIR__ . '/footer.php'; ?>
