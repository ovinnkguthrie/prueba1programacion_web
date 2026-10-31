<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'verificar_sesion.php';

$archivoJson = 'participantes.json';

// Cargar datos actuales
$participantes = file_exists($archivoJson) 
    ? json_decode(file_get_contents($archivoJson), true) ?? [] 
    : [];

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET') {
    // 2. Listar participantes
    echo json_encode($participantes);
    exit;

} elseif ($metodo === 'POST') {
    // 4. Registrar participante
    $input = json_decode(file_get_contents('php://input'), true);
    $nombre = trim($input['nombre'] ?? $_POST['nombre'] ?? '');
    $apellido = trim($input['apellido'] ?? $_POST['apellido'] ?? '');
    $numeroRifa = $input['numero_rifa'] ?? $_POST['numero_rifa'] ?? null;

    if (empty($nombre) || empty($apellido) || $numeroRifa === null) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Campos obligatorios: nombre, apellido, numero_rifa"]);
        exit;
    }

    // Generar ID autoincremental
    $nuevoId = empty($participantes) ? 1 : max(array_column($participantes, 'id')) + 1;

    $nuevoParticipante = [
        "id" => $nuevoId,
        "nombre" => $nombre,
        "apellido" => $apellido,
        "numero_rifa" => (int)$numeroRifa
    ];

    $participantes[] = $nuevoParticipante;
    file_put_contents($archivoJson, json_encode($participantes, JSON_PRETTY_PRINT));

    http_response_code(201);
    echo json_encode([
        "status" => "success",
        "message" => "Participante registrado correctamente",
        "data" => $nuevoParticipante
    ]);
    exit;
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
?>