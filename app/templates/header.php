<!-- app/templates/header.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?? 'Cartas a Papá Noel' ?></title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<header>
    <h1>Cartas a Papá Noel</h1>

    <?php if (isset($session) && $session->isLoggedIn()): ?>
        <p>
            Hola, <?= htmlspecialchars($session->getUserName()) ?>
            (
            <?= $session->isPadre() ? 'Padre/Madre' : 'Papá Noel' ?>
            )
        </p>

        <nav>
            <ul>
                <?php if ($session->isPadre()): ?>
                    <li><a href="index.php?ctl=panelPadre">Panel Padre</a></li>
                    <li><a href="index.php?ctl=crearNino">Crear Niño</a></li>

                <?php elseif ($session->isPapaNoel()): ?>
                    <li><a href="index.php?ctl=panelPapaNoel">Panel Papá Noel</a></li>
                    <li><a href="index.php?ctl=insertarJuguete">Insertar Juguete</a></li>
                <?php endif; ?>

                <li><a href="index.php?ctl=logout">Cerrar sesión</a></li>
            </ul>
        </nav>

    <?php else: ?>
        <nav>
            <ul>
                <li><a href="index.php?ctl=login">Iniciar sesión</a></li>
                <li><a href="index.php?ctl=registro">Registrarse</a></li>
                <li><a href="index.php?ctl=logout">Cerrar sesión</a></li>
            </ul>
        </nav>
    <?php endif; ?>

    <hr>
</header>
<main>
