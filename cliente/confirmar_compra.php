<?php
session_start();
include '../includes/proteger.php';
include '../config/conexion.php';

$id_usuario = $_SESSION['id_usuario'];

// Traer carrito
$sql     = "SELECT c.*, p.nombre, p.precio, p.stock 
            FROM carrito c 
            JOIN producto p ON c.id_producto = p.id_producto 
            WHERE c.id_usuario='$id_usuario'";
$items   = mysqli_query($conexion, $sql);
$carrito = [];
$total   = 0;

while ($item = mysqli_fetch_assoc($items)) {
    $item['subtotal'] = $item['precio'] * $item['cantidad'];
    $total += $item['subtotal'];
    $carrito[] = $item;
}

if (empty($carrito)) {
    header('Location: carrito.php');
    exit;
}

// Procesar compra
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Crear venta
    mysqli_query($conexion, 
        "INSERT INTO venta (id_usuario, total, estado_venta) 
         VALUES ('$id_usuario','$total','completada')");
    $id_venta = mysqli_insert_id($conexion);

    // Insertar detalles y actualizar stock
    foreach ($carrito as $item) {
        $subtotal = $item['subtotal'];
        $cantidad = $item['cantidad'];
        $id_prod  = $item['id_producto'];

        mysqli_query($conexion, 
            "INSERT INTO detalle_venta (id_venta, id_producto, cantidad, subtotal) 
             VALUES ('$id_venta','$id_prod','$cantidad','$subtotal')");

        mysqli_query($conexion, 
            "UPDATE producto SET stock = stock - '$cantidad' WHERE id_producto='$id_prod'");
    }

    // Vaciar carrito
    mysqli_query($conexion, "DELETE FROM carrito WHERE id_usuario='$id_usuario'");

    header('Location: historial.php?compra=exitosa');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Compra — GameStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark" id="navbar-main">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">
      <i class="bi bi-controller"></i> GameStore
    </a>
    <a href="carrito.php" class="btn btn-outline-warning btn-sm">
      <i class="bi bi-arrow-left"></i> Volver al carrito
    </a>
  </div>
</nav>

<div class="container mt-5 pt-4" style="max-width:600px;">
  <div class="card bg-dark text-white border-warning shadow">
    <div class="card-body p-4">
      <h4 class="fw-bold mb-4 text-center">
        <i class="bi bi-bag-check text-warning"></i> Confirmar Compra
      </h4>

      <!-- Resumen productos -->
      <?php foreach($carrito as $item): ?>
      <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-secondary">
        <div>
          <span class="fw-bold"><?= $item['nombre'] ?></span>
          <small class="text-muted ms-2">x<?= $item['cantidad'] ?></small>
        </div>
        <span class="text-warning fw-bold">$<?= number_format($item['subtotal'],2) ?></span>
      </div>
      <?php endforeach; ?>

      <div class="d-flex justify-content-between mt-3 mb-4">
        <span class="fw-bold fs-5">Total a pagar:</span>
        <span class="fw-bold fs-4 text-warning">$<?= number_format($total,2) ?></span>
      </div>

      <form method="POST">
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-warning btn-lg fw-bold">
            <i class="bi bi-check-circle"></i> Confirmar y Pagar
          </button>
          <a href="carrito.php" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>