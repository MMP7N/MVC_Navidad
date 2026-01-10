<?php
// app/modelo/Carta.php

class Carta
{
    // Obtener carta de un niño
    public static function getCartaByNino(int $idNino): ?array
    {
        $db = Database::getConexion();

        $stmt = $db->prepare("
            SELECT c.id, c.estado
            FROM cartas c
            WHERE c.id_nino = ?
        ");
        $stmt->execute([$idNino]);
        return $stmt->fetch() ?: null;
    }

    // Crear carta si no existe
    public static function crearCarta(int $idNino): int
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("INSERT INTO cartas (id_nino) VALUES (?)");
        $stmt->execute([$idNino]);
        return $db->lastInsertId();
    }

    // Obtener juguetes de una carta
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

    // Añadir juguete a carta
    public static function addJuguete(int $idCarta, int $idJuguete): void
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("
            INSERT IGNORE INTO carta_juguetes (id_carta, id_juguete)
            VALUES (?, ?)
        ");
        $stmt->execute([$idCarta, $idJuguete]);
    }
}
