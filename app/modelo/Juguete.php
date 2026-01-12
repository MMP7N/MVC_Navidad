<?php
/**
 *  Modelo para gestionar los juguetes de la aplicación.
 *  Funcionalidades:
 *    - Listar todos los juguetes
 *    - Insertar un nuevo juguete (solo Papá Noel)
 */

class Juguete
{
    /**
     * Obtiene todos los juguetes disponibles
     * @return array Array de juguetes
     */
    public static function getAll(): array
    {
        $db = Database::getConexion();
        $stmt = $db->query("SELECT * FROM juguetes");
        return $stmt->fetchAll();
    }

    /**
     * Inserta un nuevo juguete en la base de datos
     * @param string $nombre Nombre del juguete
     * @param string $descripcion Descripción del juguete
     * @param float $precio Precio del juguete
     */
    public static function insertar(string $nombre, string $descripcion, float $precio): void
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("
            INSERT INTO juguetes (nombre, descripcion, precio)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$nombre, $descripcion, $precio]);
    }
    /**
     * Permite editar un juguete existente
     * @param int $id ID del juguete
     * @param string $nombre Nuevo nombre del juguete
     * @param string $descripcion Nueva descripción del juguete
     * @param float $precio Nuevo precio del juguete
     * @return void
     */
    public static function editar(int $id, string $nombre, string $descripcion, float $precio): void
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("
            UPDATE juguetes
            SET nombre = ?, descripcion = ?, precio = ?
            WHERE id = ?
        ");
        $stmt->execute([$nombre, $descripcion, $precio, $id]);
    }
}
?>
