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

    // Verificar si ya está en el carrito
    $check = mysqli_query($conexion, 
        "SELECT * FROM carrito WHERE id_usuario='$id_usuario' AND id_producto='$id_producto'");

    if (mysqli_num_rows($check) > 0) {
        // Si ya existe, aumentar cantidad
        mysqli_query($conexion, 
            "UPDATE carrito SET cantidad = cantidad + 1 
             WHERE id_usuario='$id_usuario' AND id_producto='$id_producto'");
    } else {
        // Si no existe, agregar
        mysqli_query($conexion, 
            "INSERT INTO carrito (id_usuario, id_producto, cantidad) 
             VALUES ('$id_usuario','$id_producto', 1)");
    }
}

header('Location: carrito.php');
exit;
?>