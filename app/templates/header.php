<link rel="stylesheet" href="public/css/estilos.css">
<h2>Panel de <?= htmlspecialchars($session->getUserName()) ?></h2>

<h3>Crear un nuevo niño</h3>
<a href="index.php?ctl=crearNino">Crear Niño</a>

<h3>Hijos registrados:</h3>
<?php if (!empty($hijos)): ?>
    <ul>
        <?php foreach ($hijos as $hijo): ?>
            <li>
                <?= htmlspecialchars($hijo['nombre']) ?> 
                (<?= htmlspecialchars($hijo['edad']) ?> años) -
                <a href="index.php?ctl=verCartaHijo&idNino=<?= $hijo['id'] ?>">Ver carta</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No hay hijos registrados todavía.</p>
<?php endif; ?>
