/* =========================================================
   BASE DE DATOS: CARTAS A PAPA NOEL / REYES MAGOS
   Proyecto DAW - MVC
   ========================================================= */

DROP DATABASE IF EXISTS navidad;
CREATE DATABASE navidad
CHARACTER SET utf8mb4
COLLATE utf8mb4_spanish_ci;

USE navidad;

/* =========================================================
   TABLA USUARIOS
   Padres / Madres y Papa Noel
   ========================================================= */
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    rol ENUM('padre', 'papanoel') NOT NULL
);

/* =========================================================
   TABLA NIÑOS
   Cada niño pertenece obligatoriamente a un padre/madre
   ========================================================= */
CREATE TABLE ninos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    edad INT NOT NULL,
    id_padre INT NOT NULL,

    CONSTRAINT fk_nino_padre
        FOREIGN KEY (id_padre)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
);

/* =========================================================
   TABLA JUGUETES
   Gestionada exclusivamente por Papa Noel
   ========================================================= */
CREATE TABLE juguetes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(6,2) NOT NULL
);

/* =========================================================
   TABLA CARTAS
   Cada niño puede tener una carta
   La carta se crea como PENDIENTE por defecto
   ========================================================= */
CREATE TABLE cartas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_nino INT NOT NULL,
    estado ENUM('pendiente', 'validada') DEFAULT 'pendiente',

    CONSTRAINT fk_carta_nino
        FOREIGN KEY (id_nino)
        REFERENCES ninos(id)
        ON DELETE CASCADE,

    CONSTRAINT uq_carta_nino UNIQUE (id_nino)
);

/* =========================================================
   TABLA CARTA_JUGUETES
   Relación muchos a muchos entre cartas y juguetes
   ========================================================= */
CREATE TABLE carta_juguetes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_carta INT NOT NULL,
    id_juguete INT NOT NULL,

    CONSTRAINT fk_cj_carta
        FOREIGN KEY (id_carta)
        REFERENCES cartas(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_cj_juguete
        FOREIGN KEY (id_juguete)
        REFERENCES juguetes(id)
        ON DELETE CASCADE,

    CONSTRAINT uq_carta_juguete UNIQUE (id_carta, id_juguete)
);
