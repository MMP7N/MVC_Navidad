<?php

class Database
{
    private static ?PDO $db = null;

    public static function getConexion(): PDO
    {
        if (self::$db === null) {
            try {
                self::$db = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }

        return self::$db;
    }
}
