<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'verificar_sesion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido. Use GET."]);
    exit;
}

$archivoJson = 'premios.json';
$premios = file_exists($archivoJson) 
    ? json_decode(file_get_contents($archivoJson), true) ?? [] 
    : [];

echo json_encode($premios);
?>
