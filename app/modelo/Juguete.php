<?php
// app/modelo/Juguete.php

class Juguete
{
    // Obtener todos los juguetes
    public static function getAll(): array
    {
        $db = Database::getConexion();
        $stmt = $db->query("SELECT * FROM juguetes");
        return $stmt->fetchAll();
    }

    // Insertar juguete (Papá Noel)
    public static function insertar(string $nombre, string $descripcion, float $precio): void
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("
            INSERT INTO juguetes (nombre, descripcion, precio)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$nombre, $descripcion, $precio]);
    }
}
