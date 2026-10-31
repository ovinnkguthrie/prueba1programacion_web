<?php
session_start();

// Proteger el acceso únicamente a usuarios autenticados
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$precioUnidad = 2000;

// Inicializar la variable de sesión para los números reservados si no existe
if (!isset($_SESSION['numeros_reservados'])) {
    $_SESSION['numeros_reservados'] = 0;
}

// Procesar el formulario enviado por método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'agregar') {
            $_SESSION['numeros_reservados'] += 1;
        } elseif ($_POST['accion'] === 'quitar') {
            // Regla: No se permiten cantidades negativas
            if ($_SESSION['numeros_reservados'] > 0) {
                $_SESSION['numeros_reservados'] -= 1;
            }
        }
    }

    // Patrón Post/Redirect/Get para evitar duplicar envíos al presionar F5
    header('Location: rifa.php');
    exit;
}

$cantidad = $_SESSION['numeros_reservados'];
$total = $cantidad * $precioUnidad;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Números - Sistema de Rifa</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f1f5f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: #ffffff; padding: 2.5rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); width: 100%; max-width: 420px; text-align: center; }
        h1 { color: #0f172a; margin-top: 0; font-size: 1.6rem; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin: 20px 0; text-align: left; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.95rem; color: #475569; }
        .info-row.total { font-weight: bold; font-size: 1.2rem; color: #0f172a; border-top: 2px dashed #cbd5e1; padding-top: 10px; margin-bottom: 0; }
        .btn-group { display: flex; gap: 10px; justify-content: center; margin-bottom: 10px; }
        .btn { flex: 1; padding: 12px; border: none; border-radius: 6px; font-size: 1rem; font-weight: bold; cursor: pointer; transition: background 0.2s; }
        .btn-add { background: #22c55e; color: white; }
        .btn-add:hover { background: #16a34a; }
        .btn-remove { background: #ef4444; color: white; }
        .btn-remove:hover { background: #dc2626; }
        .btn-remove:disabled { background: #cbd5e1; cursor: not-allowed; }
        .nav-link { display: inline-block; margin-top: 15px; color: #64748b; text-decoration: none; font-size: 0.9rem; }
        .nav-link:hover { color: #0f172a; }
    </style>
</head>
<body>

<div class="card">
    <h1>🎟️ Reserva de Números</h1>
    <p style="color: #64748b; font-size: 0.9rem;">Gestione la cantidad de números de rifa a reservar.</p>

    <!-- Formulario con método POST -->
    <form method="POST" action="rifa.php">
        <div class="btn-group">
            <button type="submit" name="accion" value="quitar" class="btn btn-remove" <?= ($cantidad <= 0) ? 'disabled' : '' ?>>
                ➖ Quitar
            </button>
            <button type="submit" name="accion" value="agregar" class="btn btn-add">
                ➕ Agregar
            </button>
        </div>
    </form>

    <!-- Visualización dinámica de valores -->
    <div class="info-box">
        <div class="info-row">
            <span>Precio por unidad:</span>
            <strong>$<?= number_format($precioUnidad, 0, ',', '.') ?></strong>
        </div>
        <div class="info-row">
            <span>Números reservados:</span>
            <strong><?= $cantidad ?></strong>
        </div>
        <div class="info-row total">
            <span>Total a pagar:</span>
            <span style="color: #2563eb;">$<?= number_format($total, 0, ',', '.') ?></span>
        </div>
    </div>

    <a href="panel.php" class="nav-link">← Volver al Panel de Control</a>
</div>

</body>
</html>