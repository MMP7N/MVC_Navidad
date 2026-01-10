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
    $stmt = $db->query("
        SELECT n.nombre AS nino, c.estado, u.nombre AS padre
        FROM cartas c
        JOIN ninos n ON c.id_nino = n.id
        JOIN usuarios u ON n.id_padre = u.id
        ORDER BY c.estado
    ");
    $cartas = $stmt->fetchAll();

    $titulo = "Cartas de los niños";

    $session = $this->session;

    require __DIR__ . '/../templates/verCartas.php';
}

}
