<?php
/**
 * ================================================================
 *  Punto de entrada de la aplicación (Front Controller)
 *  ---------------------------------------------------------------
 *  Se encarga de:
 *    - Cargar librerías, modelos y controladores
 *    - Definir las rutas (enrutamiento)
 *    - Ejecutar la acción correspondiente según el parámetro "ctl"
 */

//  Carga de librerías y archivos esenciales
require_once __DIR__ . '/../app/libs/Config.php';
require_once __DIR__ . '/../app/libs/bGeneral.php';
require_once __DIR__ . '/../app/libs/bSeguridad.php';
require_once __DIR__ . '/../app/Core/autoload.php';
require_once __DIR__ . '/../app/core/Database.php';

// Controladores
require_once __DIR__ . '/../app/controlador/Controller.php';
require_once __DIR__ . '/../app/controlador/AuthController.php';
require_once __DIR__ . '/../app/controlador/PadreController.php';
require_once __DIR__ . '/../app/controlador/PapaNoelController.php';

// Modelos
require_once __DIR__ . '/../app/modelo/Usuario.php';
require_once __DIR__ . '/../app/modelo/Nino.php';
require_once __DIR__ . '/../app/modelo/Juguete.php';
require_once __DIR__ . '/../app/modelo/Carta.php';

// Crear sesión
$session = new SessionManager();
$session->checkSecurity();
//  Definición de rutas
// El parámetro "ctl" define la acción a ejecutar
$map = [
    // Rutas generales
    'inicio' => ['controller' => 'Controller', 'action' => 'inicio', 'nivel' => 0],
    'error'  => ['controller' => 'Controller', 'action' => 'error', 'nivel' => 0],

    // Autenticación
    'login'    => ['controller' => 'AuthController', 'action' => 'login', 'nivel' => 0],
    'logout'   => ['controller' => 'AuthController', 'action' => 'logout', 'nivel' => 1],
    'registro' => ['controller' => 'AuthController', 'action' => 'registro', 'nivel' => 0],

    // Panel y acciones Padre / Madre
    'panelPadre'       => ['controller' => 'PadreController', 'action' => 'panel', 'nivel' => 1],
    'crearNino'        => ['controller' => 'PadreController', 'action' => 'crearNino', 'nivel' => 1],
    'verCartaHijo'     => ['controller' => 'PadreController', 'action' => 'verCartaHijo', 'nivel' => 1],
    'crearCartaHijo'   => ['controller' => 'PadreController', 'action' => 'crearCartaHijo', 'nivel' => 1],
    'validarCarta'     => ['controller' => 'PadreController', 'action' => 'validarCarta', 'nivel' => 1],
    'quitarJuguete'    => ['controller' => 'PadreController', 'action' => 'quitarJuguete', 'nivel' => 1],

    // Panel y acciones Papá Noel
    'panelPapaNoel'    => ['controller' => 'PapaNoelController', 'action' => 'panel', 'nivel' => 2],
    'insertarJuguete'  => ['controller' => 'PapaNoelController', 'action' => 'insertarJuguete', 'nivel' => 2],
    'verCartas'        => ['controller' => 'PapaNoelController', 'action' => 'verCartas', 'nivel' => 2],
    'verJuguetes'      => ['controller' => 'PapaNoelController', 'action' => 'verJuguetes', 'nivel' => 2],
    'editarJuguete'    => ['controller' => 'PapaNoelController', 'action' => 'editarJuguete', 'nivel' => 2],
];

//  Determinar la ruta solicitada
$ruta = $_GET['ctl'] ?? 'inicio';

if (!isset($map[$ruta])) {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>Error 404: Ruta '$ruta' no encontrada</h1>";
    exit;
}

// -------------------------------------------------------------
// Comprobación de permisos
// -------------------------------------------------------------
$controlador = $map[$ruta]['controller'];
$actionName = $map[$ruta]['action'];
$requiredLevel = $map[$ruta]['nivel'];

if (!$session->hasLevel($requiredLevel)) {
    header("HTTP/1.0 403 Forbidden");
    echo "<h1>403: No tienes permisos para acceder a esta acción</h1>";
    exit;
}

// -------------------------------------------------------------
// Ejecución del controlador
// -------------------------------------------------------------
$controller = new $controlador($session);

if (!method_exists($controller, $actionName)) {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>Error 404: Acción '$actionName' no encontrada en $controlador</h1>";
    exit;
}

$controller->$actionName();
?>