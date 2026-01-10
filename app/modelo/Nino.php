<?php
/**
 * ================================================================
 *  Clase Nino
 *  ---------------------------------------------------------------
 *  Modelo para gestionar los niños dentro de la aplicación.
 *  Funcionalidades:
 *    - Crear un nuevo niño
 *    - Obtener hijos de un padre
 *    - Obtener un niño por ID
 * 
 *  Cada objeto Nino representa un registro de la tabla "ninos".
 */

class Nino
{
    public int $id;
    public string $user;
    public string $password;
    public string $nombre;
    public int $edad;
    public int $id_padre;

    /**
     * Constructor para inicializar un objeto Nino
     */
    public function __construct(
        int $id = 0,
        string $user = "",
        string $password = "",
        string $nombre = "",
        int $edad = 0,
        int $id_padre = 0
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->password = $password;
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->id_padre = $id_padre;
    }

    /**
     * Crear un nuevo niño en la base de datos
     * @param string $user Usuario del niño
     * @param string $password Contraseña en texto plano (se encripta internamente)
     * @param string $nombre Nombre completo del niño
     * @param int $edad Edad del niño
     * @param int $id_padre ID del padre que lo crea
     * @return bool True si se insertó correctamente, false si hubo error
     */
    public static function crearNino(string $user, string $password, string $nombre, int $edad, int $id_padre): bool
    {
        $db = Database::getConexion();
        $hash = encriptar($password); // Función para encriptar contraseña
        $stmt = $db->prepare("
            INSERT INTO ninos (user, password, nombre, edad, id_padre)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$user, $hash, $nombre, $edad, $id_padre]);
    }

    /**
     * Obtener todos los hijos asociados a un padre
     * @param int $id_padre ID del padre
     * @return array Array de objetos Nino
     */
    public static function getHijosByPadre(int $id_padre): array
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("SELECT * FROM ninos WHERE id_padre = ?");
        $stmt->execute([$id_padre]);

        $hijos = [];
        while ($row = $stmt->fetch()) {
            $hijos[] = new Nino(
                $row['id'],
                $row['user'],
                $row['password'],
                $row['nombre'],
                $row['edad'],
                $row['id_padre']
            );
        }
        return $hijos;
    }

    /**
     * Obtener un niño por su ID
     * @param int $id ID del niño
     * @return Nino|null Objeto Nino si existe, null si no existe
     */
    public static function getById(int $id): ?Nino
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("SELECT * FROM ninos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row) {
            return new Nino(
                $row['id'],
                $row['user'],
                $row['password'],
                $row['nombre'],
                $row['edad'],
                $row['id_padre']
            );
        }

        return null;
    }
}
?>
