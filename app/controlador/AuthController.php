<?php
// app/controlador/AuthController.php

class AuthController
{
    private SessionManager $session;

    public function __construct()
    {
        $this->session = new SessionManager();
    }

    // Registro de usuario (solo Padre o Papá Noel)
    public function registro(): void
    {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = recoge('user');
            $email = recoge('email');
            $password = recoge('password');
            $nombre = recoge('nombre');
            $rol = recoge('rol'); // 'padre' o 'papanoel'

            // Validaciones básicas
            cTexto($user, 'user', $errores, 30, 3);
            cTexto($nombre, 'nombre', $errores, 50, 3);
            cTexto($rol, 'rol', $errores, 10, 3);

            if (!in_array($rol, ['padre', 'papanoel'])) {
                $errores['rol'] = "Rol no válido";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores['email'] = "Email no válido";
            }

            if (strlen($password) < 6) {
                $errores['password'] = "La contraseña debe tener al menos 6 caracteres";
            }

            if (empty($errores)) {
                $hash = encriptar($password);

                $db = Database::getConexion();
                $stmt = $db->prepare("INSERT INTO usuarios (user, email, password, nombre, rol) VALUES (?, ?, ?, ?, ?)");
                try {
                    $stmt->execute([$user, $email, $hash, $nombre, $rol]);
                    header("Location: index.php?ctl=login");
                    exit;
                } catch (PDOException $e) {
                    $errores['general'] = "Error al registrar usuario: " . $e->getMessage();
                }
            }
        }

        $titulo = "Registro de usuario";
        require __DIR__ . '/../templates/registro.php';
    }

    // Login de usuario (Padre, Niño, Papá Noel)
    public function login(): void
    {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = recoge('user');
            $password = recoge('password');

            $db = Database::getConexion();
            $stmt = $db->prepare("SELECT * FROM usuarios WHERE user = ?");
            $stmt->execute([$user]);
            $usuario = $stmt->fetch(PDO::FETCH_OBJ); 

            if ($usuario && comprobarhash($password, $usuario->password)) {
                $nivel = 0;
                switch ($usuario->rol) {
                    case 'padre': $nivel = 1; break;
                    case 'nino': $nivel = 2; break;
                    case 'papanoel': $nivel = 3; break;
                }

                $this->session->login($usuario->id, $usuario->nombre, $nivel);

                // Redirige según rol
                switch ($nivel) {
                    case 1: header("Location: index.php?ctl=panelPadre"); break;
                    case 2: header("Location: index.php?ctl=panelNino"); break;
                    case 3: header("Location: index.php?ctl=panelPapaNoel"); break;
                }
                exit;
            } else {
                $errores['general'] = "Usuario o contraseña incorrectos";
            }
        }

        $titulo = "Login";
        require __DIR__ . '/../templates/login.php';
    }

    // Logout
    public function logout(): void
    {
        $this->session->logout();
    }
}
?>