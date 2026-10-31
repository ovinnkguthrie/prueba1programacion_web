<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

session_destroy();
echo json_encode(["status" => "success", "mensaje" => "Sesión de API finalizada"]);
?>