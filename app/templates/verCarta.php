<?php require __DIR__ . '/header.php'; ?>

<h2>Mi Carta</h2>

<p>Estado: <strong><?= strtoupper($carta['estado']) ?></strong></p>

<ul>
<?php foreach ($juguetes as $j): ?>
    <li><?= htmlspecialchars($j['nombre']) ?> - <?= $j['precio'] ?> €</li>
<?php endforeach; ?>
</ul>

<?php require __DIR__ . '/footer.php'; ?>
