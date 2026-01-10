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

// ---------------------------------------------------------------
//  Carga de librerías y archivos esenciales
// ---------------------------------------------------------------
require_once __DIR__ . '/../app/libs/Config.php';      // Configuración global
require_once __DIR__ . '/../app/libs/bGeneral.php';    // Funciones generales
require_once __DIR__ . '/../app/libs/bSeguridad.php';  // Funciones de seguridad
require_once __DIR__ . '/../app/Core/autoload.php';    // Autoload de clases
require_once __DIR__ . '/../app/core/Database.php';    // Conexión a base de datos

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

// ---------------------------------------------------------------
//  Definición de rutas
// ---------------------------------------------------------------
// El parámetro "ctl" define la acción a ejecutar
$map = [
    // Rutas generales
    'inicio' => ['controller' => 'Controller', 'action' => 'inicio'],
    'error'  => ['controller' => 'Controller', 'action' => 'error'],

    // Autenticación
    'login'    => ['controller' => 'AuthController', 'action' => 'login'],
    'logout'   => ['controller' => 'AuthController', 'action' => 'logout'],
    'registro' => ['controller' => 'AuthController', 'action' => 'registro'],

    // Panel y acciones Padre / Madre
    'panelPadre'       => ['controller' => 'PadreController', 'action' => 'panel'],
    'crearNino'        => ['controller' => 'PadreController', 'action' => 'crearNino'],
    'verCartaHijo'     => ['controller' => 'PadreController', 'action' => 'verCartaHijo'],
    'crearCartaHijo'   => ['controller' => 'PadreController', 'action' => 'crearCartaHijo'],
    'validarCarta'     => ['controller' => 'PadreController', 'action' => 'validarCarta'],
    'quitarJuguete'    => ['controller' => 'PadreController', 'action' => 'quitarJuguete'],

    // Panel y acciones Papá Noel
    'panelPapaNoel'    => ['controller' => 'PapaNoelController', 'action' => 'panel'],
    'insertarJuguete'  => ['controller' => 'PapaNoelController', 'action' => 'insertarJuguete'],
    'verCartas'        => ['controller' => 'PapaNoelController', 'action' => 'verCartas'],
];

// ---------------------------------------------------------------
//  Determinar la ruta solicitada
// ---------------------------------------------------------------
$ruta = $_GET['ctl'] ?? 'inicio';

if (!isset($map[$ruta])) {
    // Ruta no encontrada => error 404
    header('HTTP/1.0 404 Not Found');
    echo '<html><body><h1>Error 404: La ruta <i>' . htmlspecialchars($ruta) . '</i> no existe</h1></body></html>';
    exit;
}

$controlador = $map[$ruta];

// ---------------------------------------------------------------
//  Ejecutar el controlador y acción correspondiente
// ---------------------------------------------------------------
if (class_exists($controlador['controller']) && method_exists($controlador['controller'], $controlador['action'])) {
    call_user_func([new $controlador['controller'], $controlador['action']]);
} else {
    // Controlador o método no existe => error 404
    header('HTTP/1.0 404 Not Found');
    echo '<html><body><h1>Error 404: El controlador <i>' .
        htmlspecialchars($controlador['controller'] . '->' . $controlador['action']) .
        '</i> no existe</h1></body></html>';
    exit;
}
?>
