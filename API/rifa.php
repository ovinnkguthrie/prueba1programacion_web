<?php
header("Content-Type: application/json; charset=UTF-8");

$fileParticipantes = '../participantes.json';
$filePremios = '../premios.json';

// Cargar datos
$participantes = file_exists($fileParticipantes) ? json_decode(file_get_contents($fileParticipantes), true) ?? [] : [];
$premios = file_exists($filePremios) ? json_decode(file_get_contents($filePremios), true) ?? [] : [];

// Verificar disponibilidad
if (empty($participantes)) {
    http_response_code(400);
    echo json_encode(["error" => "No hay participantes registrados para la rifa."]);
    exit;
}

if (empty($premios)) {
    http_response_code(400);
    echo json_encode(["error" => "No hay premios registrados para sortear."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Selección aleatoria
    $indiceGanador = array_rand($participantes);
    $indicePremio = array_rand($premios);

    echo json_encode([
        "status" => "success",
        "ganador" => $participantes[$indiceGanador],
        "premio" => $premios[$indicePremio]
    ]);
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Método no permitido."]);
?>