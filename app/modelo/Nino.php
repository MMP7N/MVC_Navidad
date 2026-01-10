<?php
// app/modelo/Nino.php

class Nino
{
    public int $id;
    public string $user;
    public string $password;
    public string $nombre;
    public int $edad;
    public int $id_padre;

    public function __construct($id = 0, $user = "", $password = "", $nombre = "", $edad = 0, $id_padre = 0)
    {
        $this->id = $id;
        $this->user = $user;
        $this->password = $password;
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->id_padre = $id_padre;
    }

    // Crear un niño nuevo
    public static function crearNino(string $user, string $password, string $nombre, int $edad, int $id_padre): bool
    {
        $db = Database::getConexion();
        $hash = encriptar($password);
        $stmt = $db->prepare("INSERT INTO ninos (user, password, nombre, edad, id_padre) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$user, $hash, $nombre, $edad, $id_padre]);
    }

    // Obtener todos los hijos de un padre
    public static function getHijosByPadre(int $id_padre): array
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("SELECT * FROM ninos WHERE id_padre = ?");
        $stmt->execute([$id_padre]);
        $hijos = [];
        while ($row = $stmt->fetch()) {
            $hijos[] = new Nino($row['id'], $row['user'], $row['password'], $row['nombre'], $row['edad'], $row['id_padre']);
        }
        return $hijos;
    }

    // Obtener un niño por ID
    public static function getById(int $id): ?Nino
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("SELECT * FROM ninos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row) {
            return new Nino($row['id'], $row['user'], $row['password'], $row['nombre'], $row['edad'], $row['id_padre']);
        }
        return null;
    }
}
