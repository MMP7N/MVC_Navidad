<?php

/**
 * Modelo para gestionar los juguetes de la aplicación.
 * Funcionalidades:
 *   - Listar todos los juguetes
 *   - Insertar un nuevo juguete
 *   - Editar un juguete existente
 */
class Juguete
{
    /** @var PDO Conexión a la base de datos */
    private PDO $db;

    /**
     * Constructor: recibe la conexión PDO
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Obtiene todos los juguetes disponibles
     * @return array Array de juguetes
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM juguetes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserta un nuevo juguete en la base de datos
     * @param string $nombre Nombre del juguete
     * @param string $descripcion Descripción del juguete
     * @param float $precio Precio del juguete
     * @return void
     */
    public function insertar(string $nombre, string $descripcion, float $precio): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO juguetes (nombre, descripcion, precio)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$nombre, $descripcion, $precio]);
    }

    /**
     * Edita un juguete existente
     * @param int $id ID del juguete
     * @param string $nombre Nuevo nombre del juguete
     * @param string $descripcion Nueva descripción del juguete
     * @param float $precio Nuevo precio del juguete
     * @return void
     */
    public function editar(int $id, string $nombre, string $descripcion, float $precio): void
    {
        $stmt = $this->db->prepare("
            UPDATE juguetes
            SET nombre = ?, descripcion = ?, precio = ?
            WHERE id = ?
        ");
        $stmt->execute([$nombre, $descripcion, $precio, $id]);
    }
}
