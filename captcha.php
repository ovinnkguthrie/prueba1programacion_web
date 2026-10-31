<?php
session_start();

// 1. Generar dos números aleatorios entre 1 y 10
$num1 = rand(1, 10);
$num2 = rand(1, 10);

// 2. Guardar el resultado correcto de la suma en la variable de sesión
$_SESSION['captcha'] = $num1 + $num2;

// 3. Crear imagen GD para mostrar la operación matemática
$imagen = imagecreatetruecolor(140, 40);
$fondo = imagecolorallocate($imagen, 255, 255, 255);
$textoColor = imagecolorallocate($imagen, 15, 23, 42);
$lineaColor = imagecolorallocate($imagen, 203, 213, 225);

imagefilledrectangle($imagen, 0, 0, 140, 40, $fondo);

// Líneas de interferencia decorativas
for ($i = 0; $i < 3; $i++) {
    imageline($imagen, rand(0, 140), rand(0, 40), rand(0, 140), rand(0, 40), $lineaColor);
}

// Dibujar el texto de la operación: "X + Y = ?"
$texto = "$num1 + $num2 = ?";
imagestring($imagen, 5, 25, 12, $texto, $textoColor);

header('Content-Type: image/png');
header("Cache-Control: no-cache, must-revalidate");
imagepng($imagen);
imagedestroy($imagen);
?>