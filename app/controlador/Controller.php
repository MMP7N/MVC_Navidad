<?php
// app/controlador/Controller.php

/**
 * Controlador principal genérico
 * Gestiona páginas públicas o generales como inicio y error
 */
class Controller
{
    private SessionManager $session;

    public function __construct()
    {
        // Inicializa la sesión para poder usarla en vistas y redirecciones
        $this->session = new SessionManager();
    }

    /**
     * Página de inicio
     * Redirige automáticamente según el rol del usuario logueado
     * Si no hay sesión, muestra la página de bienvenida
     */
    public function inicio(): void
    {
        // Redirige automáticamente según rol
        if ($this->session->isPadre()) {
            header("Location: index.php?ctl=panelPadre");
            exit;
        }

        if ($this->session->isNino()) {
            header("Location: index.php?ctl=panelNino");
            exit;
        }

        if ($this->session->isPapaNoel()) {
            header("Location: index.php?ctl=panelPapaNoel");
            exit;
        }

        // Para visitantes sin sesión
        $titulo = "Bienvenido a Cartas a Papá Noel";
        $session = $this->session;  // Necesario para el header.php
        require __DIR__ . '/../templates/home.php';
    }

    /**
     * Página de error
     * Se muestra cuando hay rutas o permisos incorrectos
     */
    public function error(): void
    {
        $titulo = "Error";
        $session = $this->session;  // Necesario para el header.php
        require __DIR__ . '/../templates/error.php';
    }
}
?>
