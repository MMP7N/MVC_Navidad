<?php

class Controller
{
    private SessionManager $session;

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    // MENÚ / HOME
    public function menu(): void
    {
        // Si el usuario ya está logueado, redirigimos según su rol
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

        // Si no está logueado, mostramos la página home genérica
        $titulo = "Bienvenido a Cartas a Papá Noel";
        require __DIR__ . '/../templates/home.php';
    }

    public function error(): void
    {
        $titulo = "Error";
        echo "<h1>Acceso no permitido</h1>";
    }
}
