<?php
header("Content-Type: application/json; charset=UTF-8");
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido. Use POST."]);
    exit;
}

// Lectura de datos desde JSON en el cuerpo o x-www-form-urlencoded
$input = json_decode(file_get_contents('php://input'), true);
$usuario = trim($input['usuario'] ?? $_POST['usuario'] ?? '');
$clave = trim($input['clave'] ?? $_POST['clave'] ?? '');

if ($usuario === 'admin' && $clave === '1234') {
    $_SESSION['usuario'] = $usuario;
    echo json_encode([
        "status" => "success",
        "message" => "Inicio de sesión exitoso"
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Credenciales incorrectas"
    ]);
}
?>