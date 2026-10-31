<?php
session_start();

// Generar un código numérico aleatorio de 4 dígitos
$captcha_code = rand(1000, 9999);
$_SESSION['captcha'] = (string)$captcha_code;

// Crear un lienzo de 120x40 píxeles
$imagen = imagecreatetruecolor(120, 40);

// Definir colores
$fondo = imagecolorallocate($imagen, 240, 242, 245);
$texto = imagecolorallocate($imagen, 30, 41, 59);
$linea = imagecolorallocate($imagen, 148, 163, 184);

// Rellenar fondo
imagefilledrectangle($imagen, 0, 0, 120, 40, $fondo);

// Dibujar líneas de distorsión
for ($i = 0; $i < 5; $i++) {
    imageline($imagen, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $linea);
}

// Escribir el código en la imagen
imagestring($imagen, 5, 40, 12, $captcha_code, $texto);

// Enviar encabezado de imagen PNG
header("Content-Type: image/png");
imagepng($imagen);
imagedestroy($imagen);
?>