<?php

class PadreController
{
    private SessionManager $session;
    private PDO $db;
    private Nino $ninoModel;

    public function __construct()
    {
        // Seguridad
        $this->session = new SessionManager();
        $this->session->checkSecurity();

        if (!$this->session->isPadre()) {
            header("Location: index.php?ctl=error");
            exit;
        }

        // Conexión a BD
        $this->db = Database::getConexion();

        // Modelo Niño
        $this->ninoModel = new Nino($this->db);
    }

    /**
     * Panel principal del padre
     */
    public function panel(): void
    {
        $session = $this->session;
        $hijos = $this->ninoModel->getHijosByPadre($this->session->getUserId());
        $titulo = "Panel Padre";

        require __DIR__ . '/../templates/panelPadre.php';
    }

    /**
     * Crear un nuevo hijo
     */
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
                $this->ninoModel->crearNino(
                    $user,
                    $password,
                    $nombre,
                    (int)$edad,
                    $session->getUserId()
                );

                header("Location: index.php?ctl=panelPadre");
                exit;
            }
        }

        $titulo = "Crear Niño";
        require __DIR__ . '/../templates/crearNino.php';
    }

    /**
     * Ver carta de un hijo
     */
    public function verCartaHijo(): void
    {
        $session = $this->session;
        $idNino = (int)$_GET['idNino'];

        $nino = $this->ninoModel->getById($idNino);

        if (!$nino || $nino->id_padre !== $this->session->getUserId()) {
            header("Location: index.php?ctl=error");
            exit;
        }

        $carta = Carta::getCartaByNino($idNino);
        $juguetes = $carta ? Carta::getJuguetesCarta($carta['id']) : [];

        $titulo = "Carta de " . $nino->nombre;
        require __DIR__ . '/../templates/verCartaHijo.php';
    }

    /**
     * Crear o editar carta
     */
    public function crearCartaHijo(): void
    {
        $idNino = (int)$_GET['idNino'];
        $nino = $this->ninoModel->getById($idNino);

        if (!$nino || $nino->id_padre !== $this->session->getUserId()) {
            header("Location: index.php?ctl=error");
            exit;
        }

        $carta = Carta::getCartaByNino($idNino);

        if (!$carta) {
            $idCarta = Carta::crearCarta($idNino);
        } else {
            $idCarta = $carta['id'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $juguetesSeleccionados = recogeArray('juguetes');

            Carta::quitarTodosJuguetes($idCarta);

            foreach ($juguetesSeleccionados as $idJuguete) {
                Carta::addJuguete($idCarta, (int)$idJuguete);
            }

            header("Location: index.php?ctl=verCartaHijo&idNino=$idNino");
            exit;
        }

        $juguetes = Juguete::getAll();
        $juguetesEnCarta = Carta::getJuguetesCarta($idCarta);

        $titulo = "Crear carta de $nino->nombre";
        $session = $this->session;

        require __DIR__ . '/../templates/crearCartaHijo.php';
    }

    public function validarCarta(): void
    {
        $idCarta = (int)$_GET['idCarta'];
        $estado = $_GET['estado'];

        Carta::setEstado($idCarta, $estado);
        header("Location: " . $_SERVER['HTTP_REFERER']);
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
}
?>
