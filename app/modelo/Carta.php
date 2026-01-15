<?php
/**
 * Modelo para manejar las cartas de los niños en la aplicación.
 * Funcionalidades:
 *   - Crear y obtener cartas de un niño
 *   - Añadir, quitar y listar juguetes de la carta
 *   - Validar o desvalidar cartas
 */
class Carta
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
     * Obtiene la carta asociada a un niño
     * @param int $idNino
     * @return array|null
     */
    public function getCartaByNino(int $idNino): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM cartas WHERE id_nino = ?");
        $stmt->execute([$idNino]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crea una nueva carta para un niño
     * @param int $idNino
     * @return int ID de la carta creada
     */
    public function crearCarta(int $idNino): int
    {
        $stmt = $this->db->prepare("INSERT INTO cartas (id_nino) VALUES (?)");
        $stmt->execute([$idNino]);
        return $this->db->lastInsertId();
    }

    /**
     * Obtiene todos los juguetes asociados a una carta
     * @param int $idCarta
     * @return array
     */
    public function getJuguetesCarta(int $idCarta): array
    {
        $stmt = $this->db->prepare("
            SELECT j.*
            FROM carta_juguetes cj
            JOIN juguetes j ON cj.id_juguete = j.id
            WHERE cj.id_carta = ?
        ");
        $stmt->execute([$idCarta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Añade un juguete a la carta
     * @param int $idCarta
     * @param int $idJuguete
     */
    public function addJuguete(int $idCarta, int $idJuguete): void
    {
        $stmt = $this->db->prepare("
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
    public function setEstado(int $idCarta, string $estado): void
    {
        $stmt = $this->db->prepare("UPDATE cartas SET estado = ? WHERE id = ?");
        $stmt->execute([$estado, $idCarta]);
    }

    /**
     * Quita un juguete específico de la carta
     * @param int $idCarta
     * @param int $idJuguete
     */
    public function quitarJuguete(int $idCarta, int $idJuguete): void
    {
        $stmt = $this->db->prepare("
            DELETE FROM carta_juguetes
            WHERE id_carta = ? AND id_juguete = ?
        ");
        $stmt->execute([$idCarta, $idJuguete]);
    }

    /**
     * Quita todos los juguetes de una carta
     * @param int $idCarta
     */
    public function quitarTodosJuguetes(int $idCarta): void
    {
        $stmt = $this->db->prepare("DELETE FROM carta_juguetes WHERE id_carta = ?");
        $stmt->execute([$idCarta]);
    }

    /**
     * Obtiene todas las cartas con datos del niño y del padre
     * @return array
     */
    public function getTodasConNinoYPadre(): array
    {
        $stmt = $this->db->query("
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
