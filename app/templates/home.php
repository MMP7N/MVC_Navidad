<?php
// app/controlador/Controller.php

class Controller
{
    private SessionManager $session;

    public function __construct()
    {
        $this->session = new SessionManager();
    }

    // Página principal
    public function inicio(): void
    {
        // Redirigir según rol
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

        // Si no está logueado
        $titulo = "Bienvenido a Cartas a Papá Noel";
        require __DIR__ . '/../templates/home.php';
    }

    public function error(): void
    {
        $titulo = "Error";
        require __DIR__ . '/../templates/error.php';
    }
}
