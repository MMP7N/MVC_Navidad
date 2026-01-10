<?php require __DIR__ . '/header.php'; ?>

<h2>Carta del hijo</h2>

<?php if (!$carta): ?>
    <p>Este niño todavía no ha creado su carta.</p>
<?php else: ?>

<p>Estado actual: <strong><?= strtoupper($carta['estado']) ?></strong></p>

<a href="index.php?ctl=validarCarta&idCarta=<?= $carta['id'] ?>&estado=validada">✅ Validar</a> |
<a href="index.php?ctl=validarCarta&idCarta=<?= $carta['id'] ?>&estado=pendiente">⏳ Pendiente</a>

<ul>
<?php foreach ($juguetes as $j): ?>
    <li>
        <?= htmlspecialchars($j['nombre']) ?> - <?= $j['precio'] ?> €
        <a href="index.php?ctl=quitarJuguete&idCarta=<?= $carta['id'] ?>&idJuguete=<?= $j['id'] ?>">❌ Quitar</a>
    </li>
<?php endforeach; ?>
</ul>

<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
