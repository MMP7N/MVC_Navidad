<?php
// app/controlador/PadreController.php

class PadreController
{
    private SessionManager $session;

    public function __construct()
    {
        $this->session = new SessionManager();

        // Solo padres pueden acceder
        if (!$this->session->isPadre()) {
            header("Location: index.php?ctl=error");
            exit;
        }
    }

    // Panel del padre
    public function panel(): void
    {
        $hijos = Nino::getHijosByPadre($this->session->getUserId());
        $titulo = "Panel Padre";
        require __DIR__ . '/../templates/panelPadre.php';
    }

    // Crear un nuevo niño
    public function crearNino(): void
    {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = recoge('user');
            $password = recoge('password');
            $nombre = recoge('nombre');
            $edad = recoge('edad');

            cTexto($user, 'user', $errores, 30, 3);
            cTexto($nombre, 'nombre', $errores, 50, 3);
            cNum($edad, 'edad', $errores, true, 18);

            if (empty($errores)) {
                if (Nino::crearNino($user, $password, $nombre, (int)$edad, $this->session->getUserId())) {
                    header("Location: index.php?ctl=panelPadre");
                    exit;
                } else {
                    $errores['general'] = "No se pudo crear el niño, probablemente el usuario ya existe.";
                }
            }
        }

        $titulo = "Crear Niño";
        require __DIR__ . '/../templates/crearNino.php';
    }
}
