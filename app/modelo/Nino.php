<?php
/**
 * Modelo para gestionar los niños dentro de la aplicación.
 */

class Nino
{
    private PDO $db;

    public int $id;
    public string $user;
    public string $password;
    public string $nombre;
    public int $edad;
    public int $id_padre;

    /**
     * Constructor: recibe la conexión PDO
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Crear un nuevo niño
     */
    public function crearNino(string $user, string $password, string $nombre, int $edad, int $id_padre): bool
    {
        $hash = encriptar($password);

        $stmt = $this->db->prepare("
            INSERT INTO ninos (user, password, nombre, edad, id_padre)
            VALUES (?, ?, ?, ?, ?)
        ");

        return $stmt->execute([$user, $hash, $nombre, $edad, $id_padre]);
    }

    /**
     * Obtener todos los hijos de un padre
     */
    public function getHijosByPadre(int $id_padre): array
    {
        $stmt = $this->db->prepare("SELECT * FROM ninos WHERE id_padre = ?");
        $stmt->execute([$id_padre]);

        $hijos = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $n = new Nino($this->db);
            $n->id = $row['id'];
            $n->user = $row['user'];
            $n->password = $row['password'];
            $n->nombre = $row['nombre'];
            $n->edad = $row['edad'];
            $n->id_padre = $row['id_padre'];

            $hijos[] = $n;
        }

        return $hijos;
    }

    /**
     * Obtener un niño por ID
     */
    public function getById(int $id): ?Nino
    {
        $stmt = $this->db->prepare("SELECT * FROM ninos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $n = new Nino($this->db);
        $n->id = $row['id'];
        $n->user = $row['user'];
        $n->password = $row['password'];
        $n->nombre = $row['nombre'];
        $n->edad = $row['edad'];
        $n->id_padre = $row['id_padre'];

        return $n;
    }
}
?>
