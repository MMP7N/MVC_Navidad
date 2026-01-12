<?php
/**
 *  Modelo para gestionar usuarios en la aplicación.
 *  Funcionalidades:
 *    - Obtener un usuario por ID
 *    - Obtener todos los usuarios con rol "padre"
 * 
 *  Cada objeto Usuario representa un registro de la tabla "usuarios".
 */

class Usuario
{
    public int $id;
    public string $user;
    public string $email;
    public string $nombre;
    public string $rol;

    /**
     * Constructor para inicializar un objeto Usuario
     */
    public function __construct(
        int $id = 0,
        string $user = "",
        string $email = "",
        string $nombre = "",
        string $rol = ""
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->rol = $rol;
    }

    /**
     * Obtener un usuario por su ID
     * @param int $id ID del usuario
     * @return Usuario|null Objeto Usuario si existe, null si no existe
     */
    public static function getById(int $id): ?Usuario
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row) {
            return new Usuario(
                $row['id'],
                $row['user'],
                $row['email'],
                $row['nombre'],
                $row['rol']
            );
        }

        return null;
    }

    /**
     * Obtener todos los usuarios que sean padres
     * @return array Array de objetos Usuario
     */
    public static function getPadres(): array
    {
        $db = Database::getConexion();
        $stmt = $db->query("SELECT * FROM usuarios WHERE rol = 'padre'");

        $padres = [];
        while ($row = $stmt->fetch()) {
            $padres[] = new Usuario(
                $row['id'],
                $row['user'],
                $row['email'],
                $row['nombre'],
                $row['rol']
            );
        }
        return $padres;
    }
}
?>
