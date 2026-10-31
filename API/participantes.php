<?php
header("Content-Type: application/json; charset=UTF-8");
$archivo = '../participantes.json';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (file_exists($archivo)) {
        $contenido = file_get_contents($archivo);
        echo $contenido ?: '[]';
    } else {
        echo '[]';
    }
} elseif ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || empty($input['nombre']) || empty($input['dni'])) {
        http_response_code(400);
        echo json_encode(["error" => "Campos obligatorios: nombre y dni"]);
        exit;
    }

    $datos = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];
    if (!is_array($datos)) {
        $datos = [];
    }

    $nuevoParticipante = [
        "id" => time(),
        "nombre" => $input['nombre'],
        "dni" => $input['dni'],
        "comprobante" => $input['comprobante'] ?? ''
    ];

    $datos[] = $nuevoParticipante;
    file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT));

    http_response_code(201);
    echo json_encode(["mensaje" => "Participante registrado", "data" => $nuevoParticipante]);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>