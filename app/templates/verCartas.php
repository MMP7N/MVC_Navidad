<?php require __DIR__ . '/header.php'; ?>

<h2>Cartas de los niños</h2>

<table border="1" cellpadding="5">
    <tr>
        <th>Niño</th>
        <th>Padre/Madre</th>
        <th>Estado</th>
        <th>Juguetes</th>
    </tr>

    <?php foreach ($cartas as $c): ?>
        <tr>
            <td><?= htmlspecialchars($c['nino']) ?></td>
            <td><?= htmlspecialchars($c['padre']) ?></td>
            <td><?= strtoupper($c['estado']) ?></td>
            <td>
                <ul>
                    <?php if (!empty($c['juguetes'])): ?>
                        <?php foreach ($c['juguetes'] as $juguete): ?>
                            <li><?= htmlspecialchars($juguete['nombre']) ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Sin juguetes</li>
                    <?php endif; ?>
                </ul>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require __DIR__ . '/footer.php'; ?>