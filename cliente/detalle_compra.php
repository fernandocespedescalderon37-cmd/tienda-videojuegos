<?php
session_start();
include '../includes/proteger.php';
include '../config/conexion.php';

$id_usuario = $_SESSION['id_usuario'];
$id_venta   = $_GET['id'] ?? null;

if (!$id_venta) { header('Location: historial.php'); exit; }

// Verificar que la venta pertenece al usuario
$venta = mysqli_fetch_assoc(mysqli_query($conexion,
    "SELECT * FROM venta WHERE id_venta='$id_venta' AND id_usuario='$id_usuario'"));

if (!$venta) { header('Location: historial.php'); exit; }

$detalles = mysqli_query($conexion,
    "SELECT dv.*, p.nombre, p.imagen FROM detalle_venta dv
     JOIN producto p ON dv.id_producto = p.id_producto
     WHERE dv.id_venta='$id_venta'");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Compra — GameStore</title>
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
    <a href="historial.php" class="btn btn-outline-warning btn-sm">
      <i class="bi bi-arrow-left"></i> Mis compras
    </a>
  </div>
</nav>

<div class="container mt-5 pt-4" style="max-width:650px;">
  <div class="card bg-dark text-white border-warning shadow">
    <div class="card-body p-4">
      <h4 class="fw-bold mb-1">
        <i class="bi bi-receipt text-warning"></i> Pedido #<?= $venta['id_venta'] ?>
      </h4>
      <p class="text-muted small mb-4">
        <?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?>
      </p>

      <?php while($d = mysqli_fetch_assoc($detalles)): ?>
      <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary">
        <div class="d-flex align-items-center gap-3">
          <?php if($d['imagen'] && file_exists('../assets/img/'.$d['imagen'])): ?>
            <img src="../assets/img/<?= $d['imagen'] ?>" width="55" height="55"
                 style="object-fit:cover;border-radius:8px;">
          <?php else: ?>
            <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                 style="width:55px;height:55px;">
              <i class="bi bi-controller text-warning fs-4"></i>
            </div>
          <?php endif; ?>
          <div>
            <p class="fw-bold mb-0"><?= $d['nombre'] ?></p>
            <small class="text-muted">Cantidad: <?= $d['cantidad'] ?></small>
          </div>
        </div>
        <span class="text-warning fw-bold">$<?= number_format($d['subtotal'],2) ?></span>
      </div>
      <?php endwhile; ?>

      <div class="d-flex justify-content-between mt-3">
        <span class="fw-bold fs-5">Total pagado:</span>
        <span class="fw-bold fs-4 text-warning">$<?= number_format($venta['total'],2) ?></span>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>