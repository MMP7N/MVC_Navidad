<?php
/**
 * Controlador de autenticación
 * Gestiona el registro, login y logout de los usuarios
 * Roles posibles: padre, nino, papanoel
 */
class AuthController
{
    private SessionManager $session;

    public function __construct()
    {
        // Inicializamos la sesión y su gestión
        $this->session = new SessionManager();
    }

    /**
     * Registro de usuario (solo Padre o Papá Noel)
     * Valida los datos del formulario y crea un nuevo usuario en la base de datos
     */
    public function registro(): void
    {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = recoge('user');
            $email = recoge('email');
            $password = recoge('password');
            $nombre = recoge('nombre');
            $rol = recoge('rol'); 

            // Validaciones de campos
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

            // Si no hay errores, insertamos el usuario en la base de datos
            if (empty($errores)) {
                $hash = encriptar($password);

                $db = Database::getConexion();
                $stmt = $db->prepare(
                    "INSERT INTO usuarios (user, email, password, nombre, rol) VALUES (?, ?, ?, ?, ?)"
                );

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

    /**
     * Login de usuario
     * Valida credenciales y asigna sesión según rol
     */
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
                // Definir nivel según rol
                $nivel = match ($usuario->rol) {
                    'padre' => 1,
                    'nino' => 2,
                    'papanoel' => 3,
                    default => 0
                };

                // Iniciar sesión
                $this->session->login($usuario->id, $usuario->nombre, $nivel);

                // Redirigir según rol
                $redir = match ($nivel) {
                    1 => 'panelPadre',
                    2 => 'panelNino',
                    3 => 'panelPapaNoel',
                    default => 'inicio'
                };
                header("Location: index.php?ctl=$redir");
                exit;
            } else {
                $errores['general'] = "Usuario o contraseña incorrectos";
            }
        }

        $titulo = "Login";
        require __DIR__ . '/../templates/login.php';
    }

    /**
     * Logout
     * Finaliza la sesión del usuario
     */
    public function logout(): void
    {
        $this->session->logout();
        header("Location: index.php?ctl=inicio");
        exit;
    }
}
?>
