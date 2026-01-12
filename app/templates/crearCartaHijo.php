<?php require __DIR__ . '/header.php'; ?>

<h2>Crea tu carta <?= htmlspecialchars($nino->nombre) ?></h2>

<form method="POST">
    <?php foreach ($juguetes as $j): ?>
        <?php
        $checked = false;
        foreach ($juguetesEnCarta as $cj) {
            if ($cj['id'] == $j['id']) {
                $checked = true;
                break;
            }
        }
        ?>
        <label>
            <input type="checkbox" name="juguetes[]" value="<?= $j['id'] ?>" <?= $checked ? 'checked' : '' ?>>
            <?= htmlspecialchars($j['nombre']) ?> (<?= $j['precio'] ?> €)
        </label><br>
    <?php endforeach; ?>

    <button type="submit">Guardar Carta</button>
</form>

<?php require __DIR__ . '/footer.php'; ?>
