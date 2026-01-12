<?php
/**
 *  Modelo para manejar las cartas de los niños en la aplicación.
 *  Funcionalidades:
 *    - Crear y obtener cartas de un niño
 *    - Añadir, quitar y listar juguetes de la carta
 *    - Validar o desvalidar cartas
 */

class Carta
{
    /**
     * Obtiene la carta asociada a un niño
     * @param int $idNino ID del niño
     * @return array|null
     */
    public static function getCartaByNino(int $idNino): ?array
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("SELECT * FROM cartas WHERE id_nino = ?");
        $stmt->execute([$idNino]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Crea una nueva carta para un niño
     * @param int $idNino
     * @return int ID de la carta recién creada
     */
    public static function crearCarta(int $idNino): int
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("INSERT INTO cartas (id_nino) VALUES (?)");
        $stmt->execute([$idNino]);
        return $db->lastInsertId();
    }

    /**
     * Obtiene todos los juguetes asociados a una carta
     * @param int $idCarta
     * @return array
     */
    public static function getJuguetesCarta(int $idCarta): array
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("
            SELECT j.*
            FROM carta_juguetes cj
            JOIN juguetes j ON cj.id_juguete = j.id
            WHERE cj.id_carta = ?
        ");
        $stmt->execute([$idCarta]);
        return $stmt->fetchAll();
    }

    /**
     * Añade un juguete a la carta
     * @param int $idCarta
     * @param int $idJuguete
     */
    public static function addJuguete(int $idCarta, int $idJuguete): void
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("
            INSERT IGNORE INTO carta_juguetes (id_carta, id_juguete)
            VALUES (?, ?)
        ");
        $stmt->execute([$idCarta, $idJuguete]);
    }

    /**
     * Cambia el estado de la carta (validada/pendiente)
     * @param int $idCarta
     * @param string $estado
     */
    public static function setEstado(int $idCarta, string $estado): void
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("UPDATE cartas SET estado = ? WHERE id = ?");
        $stmt->execute([$estado, $idCarta]);
    }

    /**
     * Quita un juguete específico de la carta
     * @param int $idCarta
     * @param int $idJuguete
     */
    public static function quitarJuguete(int $idCarta, int $idJuguete): void
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("
            DELETE FROM carta_juguetes
            WHERE id_carta = ? AND id_juguete = ?
        ");
        $stmt->execute([$idCarta, $idJuguete]);
    }

    /**
     * Quita todos los juguetes de una carta
     * @param int $idCarta
     */
    public static function quitarTodosJuguetes(int $idCarta): void
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("DELETE FROM carta_juguetes WHERE id_carta = ?");
        $stmt->execute([$idCarta]);
    }
    /**
 * Obtiene todas las cartas con datos del niño y del padre
 * @return array
 */
public static function getTodasConNinoYPadre(): array
{
    $db = Database::getConexion();

    $stmt = $db->query("
        SELECT 
            c.id AS id_carta,
            n.id AS id_nino,
            n.nombre AS nino,
            u.nombre AS padre,
            c.estado
        FROM cartas c
        JOIN ninos n ON c.id_nino = n.id
        JOIN usuarios u ON n.id_padre = u.id
        ORDER BY c.estado
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>
