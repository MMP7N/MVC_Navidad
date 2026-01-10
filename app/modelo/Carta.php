<?php
// app/modelo/Carta.php

class Carta
{
    public static function getCartaByNino(int $idNino): ?array
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("SELECT * FROM cartas WHERE id_nino = ?");
        $stmt->execute([$idNino]);
        return $stmt->fetch() ?: null;
    }

    public static function crearCarta(int $idNino): int
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("INSERT INTO cartas (id_nino) VALUES (?)");
        $stmt->execute([$idNino]);
        return $db->lastInsertId();
    }

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

    public static function addJuguete(int $idCarta, int $idJuguete): void
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("
            INSERT IGNORE INTO carta_juguetes (id_carta, id_juguete)
            VALUES (?, ?)
        ");
        $stmt->execute([$idCarta, $idJuguete]);
    }

    // 🔴 NUEVO: Validar / desvalidar carta
    public static function setEstado(int $idCarta, string $estado): void
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("UPDATE cartas SET estado = ? WHERE id = ?");
        $stmt->execute([$estado, $idCarta]);
    }

    // 🔴 NUEVO: Quitar juguete
    public static function quitarJuguete(int $idCarta, int $idJuguete): void
    {
        $db = Database::getConexion();
        $stmt = $db->prepare("
            DELETE FROM carta_juguetes
            WHERE id_carta = ? AND id_juguete = ?
        ");
        $stmt->execute([$idCarta, $idJuguete]);
    }
// Quita todos los juguetes de una carta
public static function quitarTodosJuguetes(int $idCarta): void
{
    $db = Database::getConexion();
    $stmt = $db->prepare("DELETE FROM carta_juguetes WHERE id_carta = ?");
    $stmt->execute([$idCarta]);
}

}
