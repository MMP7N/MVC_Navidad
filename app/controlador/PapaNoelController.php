<?php
// app/controlador/PapaNoelController.php

/**
 * Controlador para usuarios con rol Papá Noel
 * Permite ver el panel principal, insertar juguetes y revisar cartas de los niños
 */
class PapaNoelController
{
    private SessionManager $session;

    public function __construct()
    {
        // Inicializa sesión y verifica que el usuario sea Papá Noel
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
        $session = $this->session; // necesario para header.php
        require __DIR__ . '/../templates/panelPapaNoel.php';
    }

    /**
     * Insertar un nuevo juguete
     * Valida nombre y precio (puede usar coma o punto) antes de insertar
     */
    public function insertarJuguete(): void
    {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = recoge('nombre');
            $descripcion = recoge('descripcion');
            $precio = str_replace(',', '.', recoge('precio')); // Normalizar coma a punto

            // Validación básica
            cTexto($nombre, 'nombre', $errores, 50, 2);

            if (!is_numeric($precio) || (float)$precio < 0) {
                $errores['precio'] = "El precio debe ser un número válido mayor o igual a 0";
            }

            // Si no hay errores, insertar
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
        $db = Database::getConexion();

        // Traemos cartas con datos del niño y del padre
        $stmt = $db->query("
            SELECT c.id AS id_carta, n.id AS id_nino, n.nombre AS nino, u.nombre AS padre, c.estado
            FROM cartas c
            JOIN ninos n ON c.id_nino = n.id
            JOIN usuarios u ON n.id_padre = u.id
            ORDER BY c.estado
        ");
        $cartas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Para cada carta, obtenemos los juguetes asociados
        foreach ($cartas as &$carta) {
            $stmt2 = $db->prepare("
                SELECT j.nombre 
                FROM carta_juguetes cj
                JOIN juguetes j ON cj.id_juguete = j.id
                WHERE cj.id_carta = ?
            ");
            $stmt2->execute([$carta['id_carta']]);
            $carta['juguetes'] = $stmt2->fetchAll(PDO::FETCH_COLUMN); // Array de nombres de juguetes
        }
        unset($carta); // Buen hábito para no dejar la referencia colgando

        $titulo = "Cartas de los niños";
        $session = $this->session;

        require __DIR__ . '/../templates/verCartas.php';
    }
}
?>
