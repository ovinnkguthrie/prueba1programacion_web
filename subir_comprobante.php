<?php
session_start();

// 1. Protección de acceso: solo disponible con sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$mensaje = '';
$tipoMensaje = ''; // 'exito' o 'error'

// 2. Procesamiento del archivo tras el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] === UPLOAD_ERR_OK) {
        $directorioDestino = 'comprobantes/';

        // Crear la carpeta /comprobantes/ si aún no existe
        if (!file_exists($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        $nombreOriginal = $_FILES['comprobante']['name'];
        $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

        // Formatos de archivo permitidos
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

        if (in_array($extension, $extensionesPermitidas)) {
            // Nombre único para evitar reemplazar archivos existentes
            $nombreUnico = 'comprobante_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
            $rutaDestino = $directorioDestino . $nombreUnico;

            // Mover archivo del directorio temporal a /comprobantes/
            if (move_uploaded_file($_FILES['comprobante']['tmp_name'], $rutaDestino)) {
                $mensaje = "El archivo se cargó correctamente: <strong>$nombreUnico</strong>";
                $tipoMensaje = 'exito';
            } else {
                $mensaje = "Ocurrió un error al intentar guardar el archivo en el servidor.";
                $tipoMensaje = 'error';
            }
        } else {
            $mensaje = "Error: Formato no permitido. Solo se aceptan imágenes (JPG, PNG, WEBP) o PDF.";
            $tipoMensaje = 'error';
        }
    } else {
        $mensaje = "Ocurrió un error al recibir el archivo o no se seleccionó ninguno.";
        $tipoMensaje = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Comprobante - Sistema de Rifa</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f1f5f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .card { background: #ffffff; padding: 2.5rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); width: 100%; max-width: 440px; }
        h1 { color: #0f172a; margin-top: 0; font-size: 1.5rem; text-align: center; }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; font-size: 0.85rem; font-weight: bold; color: #475569; margin-bottom: 0.4rem; }
        input[type="file"] { width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px; background: #f8fafc; }
        button { width: 100%; padding: 0.75rem; background: #2563eb; color: white; border: none; border-radius: 6px; font-weight: bold; font-size: 1rem; cursor: pointer; margin-top: 0.5rem; }
        button:hover { background: #1d4ed8; }
        .alert { padding: 0.85rem; border-radius: 6px; font-size: 0.9rem; margin-bottom: 1.2rem; }
        .alert-exito { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .nav-link { display: block; text-align: center; margin-top: 1.2rem; color: #64748b; text-decoration: none; font-size: 0.9rem; }
        .nav-link:hover { color: #0f172a; }
    </style>
</head>
<body>

<div class="card">
    <h1>📤 Subir Comprobante</h1>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?= $tipoMensaje ?>">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <!-- Formulario para carga de archivos con enctype obligatorio -->
    <form action="subir_comprobante.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="comprobante">Seleccione el comprobante de pago:</label>
            <input type="file" id="comprobante" name="comprobante" accept="image/*,.pdf" required>
        </div>

        <button type="submit">Cargar Archivo</button>
    </form>

    <a href="panel.php" class="nav-link">← Volver al Panel de Control</a>
</div>

</body>
</html>