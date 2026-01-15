<?php
/**
 * Modelo para gestionar usuarios en la aplicación.
 * Funcionalidades:
 *   - Obtener un usuario por ID
 *   - Obtener todos los usuarios con rol "padre"
 */

class Usuario
{
    private PDO $db;

    public int $id;
    public string $user;
    public string $email;
    public string $nombre;
    public string $rol;

    /**
     * Constructor para inicializar un objeto Usuario
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Obtener un usuario por su ID
     * @param int $id
     * @return Usuario|null
     */
    public function getById(int $id): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $usuario = new Usuario($this->db);
        $usuario->id = $row['id'];
        $usuario->user = $row['user'];
        $usuario->email = $row['email'];
        $usuario->nombre = $row['nombre'];
        $usuario->rol = $row['rol'];

        return $usuario;
    }

    /**
     * Obtener todos los usuarios que sean padres
     * @return array Array de objetos Usuario
     */
    public function getPadres(): array
    {
        $stmt = $this->db->query("SELECT * FROM usuarios WHERE rol = 'padre'");
        $padres = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuario = new Usuario($this->db);
            $usuario->id = $row['id'];
            $usuario->user = $row['user'];
            $usuario->email = $row['email'];
            $usuario->nombre = $row['nombre'];
            $usuario->rol = $row['rol'];

            $padres[] = $usuario;
        }

        return $padres;
    }
}
?>
