<?php
// web/index.php
// Carga de configuración, librerías, modelos y controladores

require_once __DIR__ . '/../app/libs/Config.php';
require_once __DIR__ . '/../app/libs/bGeneral.php';
require_once __DIR__ . '/../app/libs/bSeguridad.php';
require_once __DIR__ . '/../app/Core/autoload.php';
require_once __DIR__ . '/../app/core/Database.php';

require_once __DIR__ . '/../app/controlador/Controller.php';
require_once __DIR__ . '/../app/controlador/AuthController.php';
require_once __DIR__ . '/../app/controlador/PadreController.php';
require_once __DIR__ . '/../app/controlador/PapaNoelController.php';

require_once __DIR__ . '/../app/modelo/Usuario.php';
require_once __DIR__ . '/../app/modelo/Nino.php';
require_once __DIR__ . '/../app/modelo/Juguete.php';
require_once __DIR__ . '/../app/modelo/Carta.php';

/*
ENRUTAMIENTO
El parámetro "ctl" determina la ruta.
Cada ruta indica el controlador y la acción asociada.
*/
$map = [
    // Rutas generales
    'inicio' => ['controller' => 'Controller', 'action' => 'inicio'],
    'error'  => ['controller' => 'Controller', 'action' => 'error'],

    // Autenticación
    'login'    => ['controller' => 'AuthController', 'action' => 'login'],
    'logout'   => ['controller' => 'AuthController', 'action' => 'logout'],
    'registro' => ['controller' => 'AuthController', 'action' => 'registro'],

    // Padre / Madre
    'panelPadre'       => ['controller' => 'PadreController', 'action' => 'panel'],
    'crearNino'        => ['controller' => 'PadreController', 'action' => 'crearNino'],
    'verCartaHijo'     => ['controller' => 'PadreController', 'action' => 'verCartaHijo'],
    'crearCartaHijo'   => ['controller' => 'PadreController', 'action' => 'crearCartaHijo'],
    'validarCarta'     => ['controller' => 'PadreController', 'action' => 'validarCarta'],
    'quitarJuguete'    => ['controller' => 'PadreController', 'action' => 'quitarJuguete'],

    // Papa Noel
    'panelPapaNoel'    => ['controller' => 'PapaNoelController', 'action' => 'panel'],
    'insertarJuguete'  => ['controller' => 'PapaNoelController', 'action' => 'insertarJuguete'],
    'verCartas'        => ['controller' => 'PapaNoelController', 'action' => 'verCartas'],
];

/*
Parseo de la ruta
*/
$ruta = $_GET['ctl'] ?? 'inicio';

if (!isset($map[$ruta])) {
    header('Status: 404 Not Found');
    echo '<html><body><h1>Error 404: No existe la ruta <i>' . htmlspecialchars($ruta) . '</i></h1></body></html>';
    exit;
}

$controlador = $map[$ruta];

/*
Ejecución de la acción asociada a la ruta
*/
if (class_exists($controlador['controller']) && method_exists($controlador['controller'], $controlador['action'])) {
    call_user_func([new $controlador['controller'], $controlador['action']]);
} else {
    header('Status: 404 Not Found');
    echo '<html><body><h1>Error 404: El controlador <i>' .
        htmlspecialchars($controlador['controller'] . '->' . $controlador['action']) .
        '</i> no existe</h1></body></html>';
    exit;
}
