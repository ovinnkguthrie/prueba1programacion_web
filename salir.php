<?php
// 1. Requerido por la documentación oficial de PHP para manipular la sesión
session_start();

// 2. Vaciar todas las variables registradas en la sesión actual
$_SESSION = array();

// 3. Destruir la cookie de sesión en el navegador (según el manual de PHP)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 4. Destruir completamente los datos de la sesión guardados en el servidor
session_destroy();

// 5. Deshabilitar la caché para impedir la navegación con el botón "Atrás"
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// 6. Redirigir al inicio de sesión
header('Location: index.php');
exit;
?>