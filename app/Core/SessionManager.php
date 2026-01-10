<?php
/**
 * ================================================================
 *  SessionManager
 *  ---------------------------------------------------------------
 *  Gestión segura de sesiones para tu aplicación de "Cartas a Papá Noel"
 *  Funcionalidades:
 *    - Hardening de cookies (HttpOnly, Secure, SameSite)
 *    - Fingerprint (IP + User-Agent) para evitar secuestro de sesión
 *    - Timeout por inactividad
 *    - Roles de usuario (Guest / Padre / Niño / Papá Noel)
 *    - Regeneración de ID de sesión antifijación
 *    - Funciones convenientes para validar rol y estado
 */
class SessionManager
{
    // CONFIGURACIÓN
    private string $loginPage;
    private int $timeout;

    // Niveles de acceso
    private const ROLE_GUEST     = 0;
    private const ROLE_PADRE     = 1;
    private const ROLE_NINO      = 2;
    private const ROLE_PAPANOEL  = 3;

    /**
     * Constructor: inicializa sesión y establece timeout
     */
    public function __construct(string $loginPage = 'index.php', int $timeout = 600)
    {
        $this->loginPage = $loginPage;
        $this->timeout   = $timeout;
        $this->start();
    }

    // INICIALIZACIÓN SEGURA DE LA SESIÓN
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

            // Inicializa nivel de invitado si no existe
            if (!isset($_SESSION['usuarioNivel'])) {
                $_SESSION['usuarioNivel'] = self::ROLE_GUEST;
            }
        }
    }

    // LOGIN Y LOGOUT
    public function login($id, string $name, int $level): void
    {
        session_regenerate_id(true);

        $_SESSION['usuarioId']     = $id;
        $_SESSION['usuarioNombre'] = $name;
        $_SESSION['usuarioNivel']  = $level;

        // Fingerprint
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

    // SEGURIDAD: VERIFICACIÓN DE FINGERPRINT Y TIMEOUT
    public function checkSecurity(): void
    {
        if (!$this->isLoggedIn()) {
            return;
        }

        // Comprobación de IP + User-Agent
        $storedAddr  = $_SESSION['remoteAddr'] ?? '';
        $storedAgent = $_SESSION['userAgent'] ?? '';
        $currentAddr  = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $currentAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Agent';

        if ($storedAddr !== $currentAddr || $storedAgent !== $currentAgent) {
            $this->logout();
        }

        // Timeout por inactividad
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

    // GETTERS DE SESIÓN
    public function getUserId()        { return $_SESSION['usuarioId'] ?? null; }
    public function getUserName(): string { return $_SESSION['usuarioNombre'] ?? ''; }
    public function getUserLevel(): int   { return $_SESSION['usuarioNivel'] ?? self::ROLE_GUEST; }
    public function get(string $index)    { return $_SESSION[$index] ?? null; }

    // ESTADO Y VALIDACIÓN DE ROLES
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
    public function isPadre(): bool      { return $this->getUserLevel() === self::ROLE_PADRE; }
    public function isNino(): bool       { return $this->getUserLevel() === self::ROLE_NINO; }
    public function isPapaNoel(): bool   { return $this->getUserLevel() === self::ROLE_PAPANOEL; }
}
?>
