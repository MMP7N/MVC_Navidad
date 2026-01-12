<?php require __DIR__ . '/header.php'; ?>

<h2>Editar juguete</h2>

<?php if (!empty($errores)): ?>
    <div class="errores">
        <ul>
            <?php foreach ($errores as $campo => $mensaje): ?>
                <li><?= htmlspecialchars($mensaje) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="index.php?ctl=editarJuguete&id=<?= $juguete['id'] ?>" method="POST">
    <div>
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" 
               value="<?= htmlspecialchars($juguete['nombre'] ?? '') ?>" 
               maxlength="50" required>
    </div>
    
    <div>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" rows="4"><?= htmlspecialchars($juguete['descripcion'] ?? '') ?></textarea>
    </div>
    
    <div>
        <label for="precio">Precio (€):</label>
        <input type="text" id="precio" name="precio" 
               value="<?= htmlspecialchars(number_format($juguete['precio'] ?? 0, 2)) ?>" 
               step="0.01" required>
    </div>
    
    <div>
        <button type="submit">Guardar Cambios</button>
        <a href="index.php?ctl=verJuguetes">Cancelar</a>
    </div>
</form>

<?php require __DIR__ . '/footer.php'; ?>