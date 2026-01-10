<?php
// app/controlador/PadreController.php

class PadreController
{
    private SessionManager $session;

    public function __construct()
    {
        $this->session = new SessionManager();
        $this->session->checkSecurity();

        if (!$this->session->isPadre()) {
            header("Location: index.php?ctl=error");
            exit;
        }
    }

    public function panel(): void
    {
        $session = $this->session; // ✅ CLAVE
        $hijos = Nino::getHijosByPadre($this->session->getUserId());
        $titulo = "Panel Padre";

        require __DIR__ . '/../templates/panelPadre.php';
    }

    public function verCartaHijo(): void
{
    $session = $this->session; 

    $idNino = (int)$_GET['idNino'];
    $nino = Nino::getById($idNino);

    if (!$nino || $nino->id_padre !== $this->session->getUserId()) {
        header("Location: index.php?ctl=error");
        exit;
    }

    $carta = Carta::getCartaByNino($idNino);
    $juguetes = $carta ? Carta::getJuguetesCarta($carta->id) : [];

    $titulo = "Carta del hijo";
    require __DIR__ . '/../templates/verCartaHijo.php';
}


    public function validarCarta(): void
    {
        $idCarta = (int)$_GET['idCarta'];
        $estado = $_GET['estado'];

        Carta::setEstado($idCarta, $estado);
        header("Location: index.php?ctl=panelPadre");
        exit;
    }

    public function quitarJuguete(): void
    {
        $idCarta = (int)$_GET['idCarta'];
        $idJuguete = (int)$_GET['idJuguete'];

        Carta::quitarJuguete($idCarta, $idJuguete);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function crearNino(): void
    {
        $session = $this->session; 
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
                Nino::crearNino(
                    $user,
                    $password,
                    $nombre,
                    (int)$edad,
                    $this->session->getUserId()
                );

                header("Location: index.php?ctl=panelPadre");
                exit;
            }
        }

        $titulo = "Crear Niño";
        require __DIR__ . '/../templates/crearNino.php';
    }
}
