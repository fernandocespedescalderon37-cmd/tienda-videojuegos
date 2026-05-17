<?php
$host     = "localhost";
$usuario  = "root";
$password = "";
$base     = "tienda_videojuegos";
$puerto   = 3308;

$conexion = mysqli_connect($host, $usuario, $password, $base, $puerto);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");
?>