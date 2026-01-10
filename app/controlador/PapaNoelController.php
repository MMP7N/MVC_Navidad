<?php
// app/controlador/PapaNoelController.php

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

    // Panel principal
public function panel(): void
{
    $titulo = "Panel Papá Noel";

    $session = $this->session;

    require __DIR__ . '/../templates/panelPapaNoel.php';
}


    // Insertar juguete
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

public function verCartas(): void
{
    $db = Database::getConexion();

    // Traemos las cartas con el nombre del niño y del padre
    $stmt = $db->query("
        SELECT c.id AS id_carta, n.id AS id_nino, n.nombre AS nino, u.nombre AS padre, c.estado
        FROM cartas c
        JOIN ninos n ON c.id_nino = n.id
        JOIN usuarios u ON n.id_padre = u.id
        ORDER BY c.estado
    ");
    $cartas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ahora para cada carta, añadimos los juguetes
    foreach ($cartas as &$carta) {
        $stmt2 = $db->prepare("
            SELECT j.nombre 
            FROM carta_juguetes cj
            JOIN juguetes j ON cj.id_juguete = j.id
            WHERE cj.id_carta = ?
        ");
        $stmt2->execute([$carta['id_carta']]);
        $carta['juguetes'] = $stmt2->fetchAll(PDO::FETCH_COLUMN); // Array de nombres
    }
    unset($carta); // Buen hábito para no dejar la referencia colgando

    $titulo = "Cartas de los niños";
    $session = $this->session;

    require __DIR__ . '/../templates/verCartas.php';
}


}
