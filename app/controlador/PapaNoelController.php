<?php

/**
 * Controlador para usuarios con rol Papá Noel
 * Permite ver el panel principal, insertar juguetes y revisar cartas de los niños
 */
class PapaNoelController
{
    private SessionManager $session;
    private PDO $db;
    private Juguete $jugueteModel;
    private Carta $cartaModel;

    public function __construct()
    {
        $this->session = new SessionManager();
        $this->session->checkSecurity();

        if (!$this->session->isPapaNoel()) {
            header("Location: index.php?ctl=error");
            exit;
        }
        $this->db = Database::getConexion();
        $this->jugueteModel = new Juguete($this->db);
        $this->cartaModel = new Carta($this->db);
    }

    /**
     * Panel principal de Papá Noel
     */
    public function panel(): void
    {
        $titulo = "Panel Papá Noel";
        $session = $this->session;
        require __DIR__ . '/../templates/panelPapaNoel.php';
    }

    /**
     * Insertar un nuevo juguete
     * Valida nombre y precio antes de insertar
     */
    public function insertarJuguete(): void
    {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = recoge('nombre');
            $descripcion = recoge('descripcion');
            $precio = str_replace(',', '.', recoge('precio'));

            cTexto($nombre, 'nombre', $errores, 50, 2);

            if (!is_numeric($precio) || (float)$precio < 0) {
                $errores['precio'] = "El precio debe ser un número válido mayor o igual a 0";
            }

            if (empty($errores)) {
                $this->jugueteModel->insertar($nombre, $descripcion, (float)$precio);
                header("Location: index.php?ctl=panelPapaNoel");
                exit;
            }
        }

        $titulo = "Insertar Juguete";
        $session = $this->session;
        require __DIR__ . '/../templates/insertarJuguete.php';
    }

    /**
     * Ver todas las cartas de los niños
     * Incluye los nombres de los juguetes solicitados en cada carta
     */
    public function verCartas(): void
    {
        $cartas = $this->cartaModel->getTodasConNinoYPadre();

        foreach ($cartas as &$carta) {
            $carta['juguetes'] = $this->cartaModel->getJuguetesCarta($carta['id_carta']);
        }
        unset($carta);

        $titulo = "Cartas de los niños";
        $session = $this->session;

        require __DIR__ . '/../templates/verCartas.php';
    }
    public function verJuguetes(): void
    {
        $juguetes = $this->jugueteModel->getAll();

        $titulo = "Lista de Juguetes";
        $session = $this->session;

        require __DIR__ . '/../templates/verJuguetes.php';
    }
    public function editarJuguete(): void
    {
        $errores = [];
        $id = (int)$_GET['id'];

        $juguete = null;
        $juguetes = $this->jugueteModel->getAll();
        foreach ($juguetes as $j) {
            if ($j['id'] === $id) {
                $juguete = $j;
                break;
            }
        }

        if (!$juguete) {
            header("Location: index.php?ctl=error");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = recoge('nombre');
            $descripcion = recoge('descripcion');
            $precio = str_replace(',', '.', recoge('precio'));

            cTexto($nombre, 'nombre', $errores, 50, 2);

            if (!is_numeric($precio) || (float)$precio < 0) {
                $errores['precio'] = "El precio debe ser un número válido mayor o igual a 0";
            }

            if (empty($errores)) {
                $this->jugueteModel->editar($id, $nombre, $descripcion, (float)$precio);
                header("Location: index.php?ctl=verJuguetes");
                exit;
            }
        }

        $titulo = "Editar Juguete";
        $session = $this->session;

        // Asegúrate de pasar $juguete y $errores a la vista
        require __DIR__ . '/../templates/editarJuguete.php';
    }
}
