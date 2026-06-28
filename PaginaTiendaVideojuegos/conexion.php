<?php
$servidor = "localhost";
$usuario = "root";
$contrasena = ""; // por defecto está vacío
$basededatos = "tienda_videojuegos";

$conexion = new mysqli($servidor, $usuario, $contrasena, $basededatos);

// Verifica conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// para que los acentos y la ñ no salgan raros
$conexion->set_charset("utf8mb4");
?>
