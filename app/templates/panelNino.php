<?php require __DIR__ . '/header.php'; ?>

<h2>Hola <?= htmlspecialchars($session->getUserName()) ?></h2>

<p>
    <a href="index.php?ctl=crearCarta">Crear / Editar Carta</a> |
    <a href="index.php?ctl=verCarta">Ver Carta</a>
</p>

<?php require __DIR__ . '/footer.php'; ?>
