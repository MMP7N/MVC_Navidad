<?php
/*
Usaremos estas funciones para llevas a cabo la encriptación en el momento del registro y posteriormente en
login comprobaremos si la contraseña es correcta concomprobarHash que a su vez utiliza password_verify
*/

//PASSWORD_BCRYPT Si queremos uUsar el algoritmo CRYPT_BLOWFISH
function encriptar($password, $cost=10) {
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => $cost]);
}
    
function comprobarhash($pass, $passBD) {
        // Primero comprobamos si se ha empleado una contraseña correcta:
        return password_verify($pass, $passBD);
}
    
    
?>

