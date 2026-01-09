<?php
// web/index.php
// Carga de configuración, librerías, modelos y controladores

require_once __DIR__ . '/../app/libs/Config.php';
require_once __DIR__ . '/../app/libs/bGeneral.php';
require_once __DIR__ . '/../app/libs/bSeguridad.php';

require_once __DIR__ . '/../app/core/Database.php';

require_once __DIR__ . '/../app/controlador/Controller.php';
require_once __DIR__ . '/../app/controlador/AuthController.php';
require_once __DIR__ . '/../app/controlador/PadreController.php';
require_once __DIR__ . '/../app/controlador/NinoController.php';
require_once __DIR__ . '/../app/controlador/PapaNoelController.php';

require_once __DIR__ . '/../app/modelo/Usuario.php';
require_once __DIR__ . '/../app/modelo/Nino.php';
require_once __DIR__ . '/../app/modelo/Juguete.php';
require_once __DIR__ . '/../app/modelo/Carta.php';

/*
Inicio de sesión.
Si no hay usuario logueado, se considera visitante
*/
session_start();

/*
ENRUTAMIENTO
El parámetro "ctl" determina la ruta.
Cada ruta indica el controlador y la acción asociada.
*/
$map = array(

    // Rutas generales
    'inicio' => array('controller' => 'Controller', 'action' => 'inicio'),
    'error'  => array('controller' => 'Controller', 'action' => 'error'),

    // Autenticación
    'login'    => array('controller' => 'AuthController', 'action' => 'login'),
    'logout'   => array('controller' => 'AuthController', 'action' => 'logout'),
    'registro' => array('controller' => 'AuthController', 'action' => 'registro'),

    // Padre / Madre
    'panelPadre'      => array('controller' => 'PadreController', 'action' => 'panel'),
    'validarCarta'    => array('controller' => 'PadreController', 'action' => 'validarCarta'),
    'quitarJuguete'   => array('controller' => 'PadreController', 'action' => 'quitarJuguete'),

    // Niño
    'panelNino'   => array('controller' => 'NinoController', 'action' => 'panel'),
    'crearCarta'  => array('controller' => 'NinoController', 'action' => 'crearCarta'),
    'verCarta'    => array('controller' => 'NinoController', 'action' => 'verCarta'),

    // Papa Noel
    'panelPapaNoel' => array('controller' => 'PapaNoelController', 'action' => 'panel'),
    'insertarJuguete' => array('controller' => 'PapaNoelController', 'action' => 'insertarJuguete'),
    'verCartas'      => array('controller' => 'PapaNoelController', 'action' => 'verCartas')
);

/*
Parseo de la ruta
*/
if (isset($_GET['ctl'])) {
    if (isset($map[$_GET['ctl']])) {
        $ruta = $_GET['ctl'];
    } else {
        header('Status: 404 Not Found');
        echo '<html><body><h1>Error 404: No existe la ruta <i>' .
            htmlspecialchars($_GET['ctl']) .
            '</i></h1></body></html>';
        exit;
    }
} else {
    $ruta = 'inicio';
}

$controlador = $map[$ruta];

/*
Ejecución de la acción asociada a la ruta
*/
if (method_exists($controlador['controller'], $controlador['action'])) {
    call_user_func(
        array(
            new $controlador['controller'],
            $controlador['action']
        )
    );
} else {
    header('Status: 404 Not Found');
    echo '<html><body><h1>Error 404: El controlador <i>' .
        $controlador['controller'] .
        '->' .
        $controlador['action'] .
        '</i> no existe</h1></body></html>';
}
