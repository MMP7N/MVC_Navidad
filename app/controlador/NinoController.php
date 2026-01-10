<?php
// app/controlador/NinoController.php

class NinoController
{
    private SessionManager $session;

    public function __construct()
    {
        $this->session = new SessionManager();

        if (!$this->session->isNino()) {
            header("Location: index.php?ctl=error");
            exit;
        }
    }

    // Panel del niño
    public function panel(): void
    {
        $idNino = $this->session->getUserId();
        $carta = Carta::getCartaByNino($idNino);

        $titulo = "Panel Niño";
        require __DIR__ . '/../templates/panelNino.php';
    }

    // Crear carta
    public function crearCarta(): void
    {
        $idNino = $this->session->getUserId();
        $carta = Carta::getCartaByNino($idNino);

        if (!$carta) {
            $idCarta = Carta::crearCarta($idNino);
        } else {
            $idCarta = $carta['id'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $juguetes = recogeArray('juguetes');
            foreach ($juguetes as $idJuguete) {
                Carta::addJuguete($idCarta, (int)$idJuguete);
            }
            header("Location: index.php?ctl=verCarta");
            exit;
        }

        $juguetes = Juguete::getAll();
        $titulo = "Crear Carta";
        require __DIR__ . '/../templates/crearCarta.php';
    }

    // Ver carta
    public function verCarta(): void
    {
        $idNino = $this->session->getUserId();
        $carta = Carta::getCartaByNino($idNino);

        if (!$carta) {
            header("Location: index.php?ctl=crearCarta");
            exit;
        }

        $juguetes = Carta::getJuguetesCarta($carta['id']);
        $titulo = "Mi Carta";
        require __DIR__ . '/../templates/verCarta.php';
    }
}
