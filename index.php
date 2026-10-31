<?php
session_start();

// Si ya existe sesión activa, redirige al panel
if (isset($_SESSION['usuario'])) {
    header('Location: panel.php');
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $clave = trim($_POST['clave'] ?? '');
    $respuestaCaptcha = trim($_POST['captcha'] ?? '');

    // Obtener resultado esperado de la sesión
    $resultadoEsperado = $_SESSION['captcha'] ?? null;

    // REQUERIMIENTO: Invalida el CAPTCHA actual tras el intento para exigir uno nuevo
    unset($_SESSION['captcha']);

    // 1. Verificar CAPTCHA (Respuesta matemática correcta)
    if ($resultadoEsperado === null || $respuestaCaptcha === '' || (int)$respuestaCaptcha !== (int)$resultadoEsperado) {
        $error = "La respuesta de la operación matemática es incorrecta.";
    } 
    // 2 y 3. Verificar Usuario y Contraseña
    else if ($usuario === 'admin' && $clave === '1234') {
        $_SESSION['usuario'] = $usuario;
        header('Location: panel.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Rifa</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f1f5f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: #ffffff; padding: 2.5rem; border-radius: 10px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); width: 100%; max-width: 360px; }
        h2 { text-align: center; margin-top: 0; margin-bottom: 1.5rem; color: #1e293b; }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; margin-bottom: 0.4rem; font-size: 0.9rem; color: #475569; font-weight: bold; }
        input[type="text"], input[type="password"], input[type="number"] { width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.95rem; }
        input:focus { outline: none; border-color: #2563eb; }
        .captcha-box { display: flex; align-items: center; gap: 10px; margin-bottom: 0.5rem; }
        .captcha-box img { border-radius: 6px; border: 1px solid #cbd5e1; height: 40px; }
        .btn-reload { background: #e2e8f0; border: none; padding: 0.5rem; border-radius: 6px; cursor: pointer; font-size: 1.1rem; }
        button[type="submit"] { width: 100%; padding: 0.75rem; background-color: #2563eb; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 1rem; margin-top: 0.5rem; }
        button[type="submit"]:hover { background-color: #1d4ed8; }
        .alert-danger { background-color: #fee2e2; border-left: 4px solid #ef4444; color: #991b1b; padding: 0.75rem; font-size: 0.85rem; border-radius: 4px; margin-bottom: 1.2rem; }
    </style>
</head>
<body>

<div class="card">
    <h2>Sistema de Rifa</h2>

    <?php if (!empty($error)): ?>
        <div class="alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <div class="form-group">
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" id="usuario" required placeholder="admin">
        </div>

        <div class="form-group">
            <label for="clave">Contraseña</label>
            <input type="password" name="clave" id="clave" required placeholder="1234">
        </div>

        <div class="form-group">
            <label for="captcha">Resuelva la operación</label>
            <div class="captcha-box">
                <img src="captcha.php" id="imgCaptcha" alt="CAPTCHA Matemático">
                <button type="button" class="btn-reload" onclick="document.getElementById('imgCaptcha').src='captcha.php?'+Math.random()" title="Generar nuevo CAPTCHA">🔄</button>
            </div>
            <input type="number" name="captcha" id="captcha" placeholder="Resultado (ej: 11)" required autocomplete="off">
        </div>

        <button type="submit">Ingresar</button>
    </form>
</div>

</body>
</html>
