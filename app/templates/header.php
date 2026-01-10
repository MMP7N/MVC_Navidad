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
        <p>Hola, <?= htmlspecialchars($session->getUserName()) ?></p>
        <a href="index.php?ctl=logout">Cerrar sesión</a>
    <?php endif; ?>

    <hr>
</header>
<main>
