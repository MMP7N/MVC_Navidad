<?php
// app/modelo/Usuario.php

class Usuario
{
    public int $id;
    public string $user;
    public string $email;
    public string $nombre;
    public string $rol;

    public function __construct($id = 0, $user = "", $email = "", $nombre = "", $rol = "")
    {
        $this->id = $id;
        $this->user = $user;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->rol = $rol;
    }

    // Obtener usuario por ID
    public static function getById(int $id): ?Usuario
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row) {
            return new Usuario($row['id'], $row['user'], $row['email'], $row['nombre'], $row['rol']);
        }
        return null;
    }

    // Obtener todos los padres
    public static function getPadres(): array
    {
        $db = Database::getConexion();
        $stmt = $db->query("SELECT * FROM usuarios WHERE rol = 'padre'");
        $padres = [];
        while ($row = $stmt->fetch()) {
            $padres[] = new Usuario($row['id'], $row['user'], $row['email'], $row['nombre'], $row['rol']);
        }
        return $padres;
    }
}
