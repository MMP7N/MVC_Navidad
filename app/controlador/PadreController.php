<?php
/**
 * Controlador para usuarios con rol Padre
 * Gestiona la visualización de hijos, creación de niños y cartas
 */
class PadreController
{
    private SessionManager $session;

    public function __construct()
    {
        // Inicializa la sesión y verifica que el usuario sea Padre
        $this->session = new SessionManager();
        $this->session->checkSecurity();

        if (!$this->session->isPadre()) {
            header("Location: index.php?ctl=error");
            exit;
        }
    }

    /**
     * Panel principal del padre
     * Muestra los hijos registrados
     */
    public function panel(): void
    {
        $session = $this->session;
        $hijos = Nino::getHijosByPadre($this->session->getUserId());
        $titulo = "Panel Padre";

        require __DIR__ . '/../templates/panelPadre.php';
    }

    /**
     * Crear un nuevo hijo
     * Recoge datos del formulario, valida y crea un registro en la base de datos
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
                Nino::crearNino(
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
     * Ver la carta de un hijo
     * Muestra los juguetes que ya ha pedido
     */
    public function verCartaHijo(): void
    {
        $session = $this->session;
        $idNino = (int)$_GET['idNino'];
        $nino = Nino::getById($idNino);

        // Seguridad: solo el padre puede ver la carta de su hijo
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
     * Crear o editar la carta de un hijo
     * Permite marcar o desmarcar juguetes y guardarlos
     */
    public function crearCartaHijo(): void
    {
        $idNino = (int)$_GET['idNino'];
        $nino = Nino::getById($idNino);

        // Seguridad: solo el padre del niño puede acceder
        if (!$nino || $nino->id_padre !== $this->session->getUserId()) {
            header("Location: index.php?ctl=error");
            exit;
        }

        // Obtener carta existente o crear nueva
        $carta = Carta::getCartaByNino($idNino);
        if (!$carta) {
            $idCarta = Carta::crearCarta($idNino);
            $carta = Carta::getCartaByNino($idCarta);
        } else {
            $idCarta = $carta['id'];
        }

        // Procesar formulario: marcar/desmarcar juguetes
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $juguetesSeleccionados = recogeArray('juguetes');

            Carta::quitarTodosJuguetes($idCarta);

            // Añadir solo los seleccionados
            foreach ($juguetesSeleccionados as $idJuguete) {
                Carta::addJuguete($idCarta, (int)$idJuguete);
            }

            header("Location: index.php?ctl=verCartaHijo&idNino=$idNino");
            exit;
        }

        // Obtener todos los juguetes y los que ya están en la carta
        $juguetes = Juguete::getAll();
        $juguetesEnCarta = Carta::getJuguetesCarta($idCarta);

        $titulo = "Crear carta de $nino->nombre";
        $session = $this->session;

        require __DIR__ . '/../templates/crearCartaHijo.php';
    }

    /**
     * Validar carta: marcar como validada o pendiente
     */
    public function validarCarta(): void
    {
        $idCarta = (int)$_GET['idCarta'];
        $estado = $_GET['estado'];

        Carta::setEstado($idCarta, $estado);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    /**
     * Quitar un juguete de la carta
     */
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
