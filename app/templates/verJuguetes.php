<?php require __DIR__ . '/header.php'; ?>
<h2>Listado de Juguetes</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($juguetes as $juguete): ?>
        <tr>
            <td><?= htmlspecialchars($juguete['nombre']) ?></td>
            <td><?= htmlspecialchars($juguete['descripcion']) ?></td>
            <td><?= number_format($juguete['precio'], 2) ?> €</td>
            <td>
                <a href="index.php?ctl=editarJuguete&id=<?= $juguete['id'] ?>">Editar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require __DIR__ . '/footer.php'; ?>