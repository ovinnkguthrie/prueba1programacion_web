<?php
session_start();

// 1. Protección de la página mediante verificación de sesión activa
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$directorio = 'comprobantes/';
$archivos = [];

// 2. Escanear la carpeta comprobantes/ si existe
if (file_exists($directorio) && is_dir($directorio)) {
    $elementos = scandir($directorio);
    foreach ($elementos as $item) {
        // Excluir directorios . y .. para listar solo archivos válidos
        if ($item !== '.' && $item !== '..' && is_file($directorio . $item)) {
            $archivos[] = [
                'nombre' => $item,
                'tamano' => round(filesize($directorio . $item) / 1024, 2), // Tamaño en KB
                'fecha'  => date("d/m/Y H:i", filemtime($directorio . $item))
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualización de Archivos - Sistema de Rifa</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f1f5f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .card { background: #ffffff; padding: 2rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); width: 100%; max-width: 600px; }
        h1 { color: #0f172a; margin-top: 0; font-size: 1.5rem; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 0.9rem; }
        th { background-color: #f8fafc; color: #475569; font-weight: bold; }
        .btn-view { color: #2563eb; text-decoration: none; font-weight: bold; }
        .btn-view:hover { text-decoration: underline; }
        .empty-msg { text-align: center; color: #64748b; padding: 25px 0; font-style: italic; }
        .nav-link { display: block; text-align: center; margin-top: 1.5rem; color: #64748b; text-decoration: none; font-size: 0.9rem; }
        .nav-link:hover { color: #0f172a; }
    </style>
</head>
<body>

<div class="card">
    <h1>📁 Comprobantes Almacenados</h1>

    <?php if (empty($archivos)): ?>
        <div class="empty-msg">No se encontraron archivos en la carpeta de comprobantes.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nombre del Archivo</th>
                    <th>Tamaño</th>
                    <th>Fecha de Subida</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($archivos as $archivo): ?>
                    <tr>
                        <td><?= htmlspecialchars($archivo['nombre']) ?></td>
                        <td><?= $archivo['tamano'] ?> KB</td>
                        <td><?= $archivo['fecha'] ?></td>
                        <td>
                            <a href="comprobantes/<?= rawurlencode($archivo['nombre']) ?>" target="_blank" class="btn-view">📎 Ver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="panel.php" class="nav-link">← Volver al Panel de Control</a>
</div>

</body>
</html>