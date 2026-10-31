<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'verificar_sesion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido. Use GET."]);
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Se requiere el parámetro ID en la URL."]);
    exit;
}

$archivoJson = 'participantes.json';
$participantes = file_exists($archivoJson) 
    ? json_decode(file_get_contents($archivoJson), true) ?? [] 
    : [];

$inicialCount = count($participantes);
$participantes = array_values(array_filter($participantes, function($p) use ($id) {
    return (int)$p['id'] !== (int)$id;
}));

if (count($participantes) < $inicialCount) {
    file_put_contents($archivoJson, json_encode($participantes, JSON_PRETTY_PRINT));
    echo json_encode(["status" => "success", "message" => "Participante eliminado correctamente."]);
} else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Participante no encontrado."]);
}
?>