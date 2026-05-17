<?php
session_start();
include '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];
    $id_usuario  = $_SESSION['id_usuario'];

    // Verificar si ya está en favoritos
    $check = mysqli_query($conexion,
        "SELECT * FROM favorito WHERE id_usuario='$id_usuario' AND id_producto='$id_producto'");

    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conexion,
            "INSERT INTO favorito (id_usuario, id_producto) VALUES ('$id_usuario','$id_producto')");
    }
}

header('Location: favoritos.php');
exit;
?>