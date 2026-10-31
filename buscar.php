<?php
session_start();

// Definición previa de los 4 participantes válidos requeridos
$participantesValidos = [
    [
        'nombre'    => 'Universidad',
        'apellido'  => 'Playa Ancha',
        'ciudad'    => 'Valparaiso',
        'categoria' => 'Grupo A'
    ],
    [
        'nombre'    => 'Juan',
        'apellido'  => 'Pérez',
        'ciudad'    => 'Santiago',
        'categoria' => 'Grupo B'
    ],
    [
        'nombre'    => 'María',
        'apellido'  => 'Gómez',
        'ciudad'    => 'Viña del Mar',
        'categoria' => 'Grupo A'
    ],
    [
        'nombre'    => 'Carlos',
        'apellido'  => 'Rojas',
        'ciudad'    => 'Concepción',
        'categoria' => 'Grupo C'
    ]
];

$mensaje = null;
$encontrado = false;

// Captura y evaluación de datos cuando se envía el formulario vía GET
if (isset($_GET['nombre'], $_GET['apellido'], $_GET['ciudad'], $_GET['categoria'])) {
    $nombre    = trim($_GET['nombre']);
    $apellido  = trim($_GET['apellido']);
    $ciudad    = trim($_GET['ciudad']);
    $categoria = trim($_GET['categoria']);

    // Verificar si se completaron todos los campos requeridos
    if ($nombre !== '' && $apellido !== '' && $ciudad !== '' && $categoria !== '') {
        foreach ($participantesValidos as $p) {
            // Comparación insensible a mayúsculas/minúsculas evaluando las 4 condiciones obligatorias
            if (
                mb_strtolower($p['nombre'])    === mb_strtolower($nombre) &&
                mb_strtolower($p['apellido'])  === mb_strtolower($apellido) &&
                mb_strtolower($p['ciudad'])    === mb_strtolower($ciudad) &&
                mb_strtolower($p['categoria']) === mb_strtolower($categoria)
            ) {
                $encontrado = true;
                break;
            }
        }

        $mensaje = $encontrado ? "Participante encontrado" : "Participante no encontrado";
    } else {
        $mensaje = "Por favor, complete todos los campos del formulario.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Participantes</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f1f5f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .card { background: #ffffff; padding: 2rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); width: 100%; max-width: 440px; }
        h1 { color: #0f172a; margin-top: 0; font-size: 1.5rem; text-align: center; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-size: 0.85rem; font-weight: bold; color: #475569; margin-bottom: 0.4rem; }
        input[type="text"], select { width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.95rem; }
        input:focus, select:focus { outline: none; border-color: #2563eb; }
        button { width: 100%; padding: 0.75rem; background: #2563eb; color: white; border: none; border-radius: 6px; font-weight: bold; font-size: 1rem; cursor: pointer; margin-top: 0.5rem; }
        button:hover { background: #1d4ed8; }
        .alert { padding: 0.85rem; border-radius: 6px; font-weight: bold; text-align: center; margin-bottom: 1.2rem; }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-warning { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .nav-link { display: block; text-align: center; margin-top: 1.2rem; color: #64748b; text-decoration: none; font-size: 0.9rem; }
        .nav-link:hover { color: #0f172a; }
    </style>
</head>
<body>

<div class="card">
    <h1>🔍 Buscar Participante</h1>

    <?php if ($mensaje !== null): ?>
        <?php if ($encontrado): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($mensaje) ?></div>
        <?php elseif ($mensaje === "Participante no encontrado"): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($mensaje) ?></div>
        <?php else: ?>
            <div class="alert alert-warning">⚠️ <?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Formulario configurado con método GET -->
    <form method="GET" action="buscar.php">
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($_GET['nombre'] ?? '') ?>" placeholder="Ej: Universidad" required>
        </div>

        <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido" value="<?= htmlspecialchars($_GET['apellido'] ?? '') ?>" placeholder="Ej: Playa Ancha" required>
        </div>

        <div class="form-group">
            <label for="ciudad">Ciudad</label>
            <input type="text" id="ciudad" name="ciudad" value="<?= htmlspecialchars($_GET['ciudad'] ?? '') ?>" placeholder="Ej: Valparaiso" required>
        </div>

        <div class="form-group">
            <label for="categoria">Categoría</label>
            <input type="text" id="categoria" name="categoria" value="<?= htmlspecialchars($_GET['categoria'] ?? '') ?>" placeholder="Ej: Grupo A" required>
        </div>

        <button type="submit">Buscar</button>
    </form>

    <a href="panel.php" class="nav-link">← Volver al Panel de Control</a>
</div>

</body>
</html>