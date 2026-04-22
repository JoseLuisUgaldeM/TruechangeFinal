<?php
// Aseguramos que no haya salida previa
ob_start();
session_start();

// Limpiar variables
$_SESSION = array();

// Destruir cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir sesión en servidor
session_destroy();

// Evitar que el navegador guarde en caché la página protegida
header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 

// Redirigir
header("Location:index.php");
ob_end_flush();
exit();
?>