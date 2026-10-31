<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'verificar_sesion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido. Use POST."]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? $_POST['id'] ?? null;
$nombre = trim($input['nombre'] ?? $_POST['nombre'] ?? '');
$apellido = trim($input['apellido'] ?? $_POST['apellido'] ?? '');
$numeroRifa = $input['numero_rifa'] ?? $_POST['numero_rifa'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Se requiere el ID del participante."]);
    exit;
}

$archivoJson = 'participantes.json';
$participantes = file_exists($archivoJson) 
    ? json_decode(file_get_contents($archivoJson), true) ?? [] 
    : [];

$encontrado = false;

foreach ($participantes as &$p) {
    if ((int)$p['id'] === (int)$id) {
        if (!empty($nombre)) $p['nombre'] = $nombre;
        if (!empty($apellido)) $p['apellido'] = $apellido;
        if ($numeroRifa !== null) $p['numero_rifa'] = (int)$numeroRifa;
        $encontrado = true;
        break;
    }
}

if ($encontrado) {
    file_put_contents($archivoJson, json_encode($participantes, JSON_PRETTY_PRINT));
    echo json_encode(["status" => "success", "message" => "Participante actualizado correctamente."]);
} else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Participante no encontrado."]);
}
?>