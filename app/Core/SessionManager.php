<?php
/**
 * ================================================================
 *  SessionManager
 *  ---------------------------------------------------------------
 *  Gestión segura de sesiones con:
 *    - Cookie hardening
 *    - Fingerprint (IP + User-Agent)
 *    - Timeout por inactividad
 *    - RBAC numérico (Guest/Padre/Niño/Papa Noel)
 *    - Regeneración antifijación
 *
 */

class SessionManager
{
    // -------------------------------------------------------------
    // CONFIGURACIÓN
    // -------------------------------------------------------------
    private string $loginPage;
    private int $timeout;

    // Niveles de acceso
    private const ROLE_GUEST     = 0;  // Invitado
    private const ROLE_PADRE     = 1;  // Padre/Madre
    private const ROLE_NINO      = 2;  // Niño
    private const ROLE_PAPANOEL  = 3;  // Papa Noel

    public function __construct(
        string $loginPage = 'index.php',
        int $timeout = 600
    ) {
        $this->loginPage = $loginPage;
        $this->timeout   = $timeout;

        $this->start();
    }

    // -------------------------------------------------------------
    // APERTURA SEGURA DE SESIÓN
    // -------------------------------------------------------------
    public function start(array $options = []): void
    {
        $config = array_merge([
            'httponly' => true,
            'samesite' => 'Lax',
            'secure'   => false
        ], $options);

        ini_set('session.cookie_httponly', $config['httponly'] ? '1' : '0');
        ini_set('session.cookie_samesite', $config['samesite']);
        ini_set('session.cookie_secure',   $config['secure'] ? '1' : '0');
        ini_set('session.use_strict_mode', '1');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();

            if (!isset($_SESSION['usuarioNivel'])) {
                $_SESSION['usuarioNivel'] = self::ROLE_GUEST;
            }
        }
    }

    // -------------------------------------------------------------
    // LOGIN Y LOGOUT
    // -------------------------------------------------------------
    public function login($id, string $name, int $level): void
    {
        session_regenerate_id(true);

        $_SESSION['usuarioId']     = $id;
        $_SESSION['usuarioNombre'] = $name;
        $_SESSION['usuarioNivel']  = $level;

        $_SESSION['remoteAddr'] = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $_SESSION['userAgent']  = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Agent';

        $this->refreshActivity();
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();

        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );

        header("Location: {$this->loginPage}");
        exit;
    }

    // -------------------------------------------------------------
    // SEGURIDAD: FINGERPRINT + TIMEOUT
    // -------------------------------------------------------------
    public function checkSecurity(): void
    {
        if (!$this->isLoggedIn()) {
            return;
        }

        // Fingerprint
        $storedAddr  = $_SESSION['remoteAddr'] ?? '';
        $storedAgent = $_SESSION['userAgent'] ?? '';

        $currentAddr  = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $currentAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Agent';

        if ($storedAddr !== $currentAddr || $storedAgent !== $currentAgent) {
            $this->logout();
        }

        // Timeout
        if (isset($_SESSION['lastActivity']) &&
            (time() - $_SESSION['lastActivity'] > $this->timeout)) {
            $this->logout();
        }

        $this->refreshActivity();
    }

    private function refreshActivity(): void
    {
        $_SESSION['lastActivity'] = time();
    }

    // -------------------------------------------------------------
    // GETTERS
    // -------------------------------------------------------------
    public function getUserId()
    {
        return $_SESSION['usuarioId'] ?? null;
    }

    public function getUserName(): string
    {
        return $_SESSION['usuarioNombre'] ?? '';
    }

    public function getUserLevel(): int
    {
        return $_SESSION['usuarioNivel'] ?? self::ROLE_GUEST;
    }

    public function get(string $index)
    {
        return $_SESSION[$index] ?? null;       
    }

    // -------------------------------------------------------------
    // ESTADO Y RBAC
    // -------------------------------------------------------------
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['usuarioId']) &&
               $this->getUserLevel() > self::ROLE_GUEST;
    }

    public function hasLevel(int $requiredLevel): bool
    {
        return $this->getUserLevel() >= $requiredLevel;
    }

    // Funciones convenientes para cada rol
    public function isPadre(): bool
    {
        return $this->getUserLevel() === self::ROLE_PADRE;
    }

    public function isNino(): bool
    {
        return $this->getUserLevel() === self::ROLE_NINO;
    }

    public function isPapaNoel(): bool
    {
        return $this->getUserLevel() === self::ROLE_PAPANOEL;
    }
}
?>
