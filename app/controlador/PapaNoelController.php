<?php
/**
 * Controlador para usuarios con rol Papá Noel
 * Permite ver el panel principal, insertar juguetes y revisar cartas de los niños
 */
class PapaNoelController
{
    private SessionManager $session;

    public function __construct()
    {
        $this->session = new SessionManager();

        if (!$this->session->isPapaNoel()) {
            header("Location: index.php?ctl=error");
            exit;
        }
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
                Juguete::insertar($nombre, $descripcion, (float)$precio);
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
    $cartas = Carta::getTodasConNinoYPadre();

    foreach ($cartas as &$carta) {
        $carta['juguetes'] = Carta::getJuguetesCarta($carta['id_carta']);
    }
    unset($carta);

    $titulo = "Cartas de los niños";
    $session = $this->session;

    require __DIR__ . '/../templates/verCartas.php';
}

}
?>
