<?php
session_start();
include '../includes/proteger.php';
include '../config/conexion.php';

if ($_SESSION['rol'] != 'admin') {
    header('Location: ../cliente/dashboard.php');
    exit;
}

// Estadísticas
$total_productos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as total FROM producto"))['total'];
$total_usuarios  = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as total FROM usuario"))['total'];
$total_ventas    = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as total FROM venta"))['total'];
$total_ingresos  = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT SUM(total) as total FROM venta WHERE estado_venta='completada'"))['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin — GameStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR ADMIN -->
<nav class="navbar navbar-expand-lg navbar-dark" id="navbar-main">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="dashboard.php">
      <i class="bi bi-controller"></i> GameStore <span class="badge bg-warning text-dark">Admin</span>
    </a>
    <div class="navbar-nav flex-row gap-3 ms-auto align-items-center">
      <a class="nav-link text-white" href="productos.php"><i class="bi bi-box"></i> Productos</a>
      <a class="nav-link text-white" href="categorias.php"><i class="bi bi-grid"></i> Categorías</a>
      <a class="nav-link text-white" href="ventas.php"><i class="bi bi-receipt"></i> Ventas</a>
      <a class="nav-link text-white" href="usuarios.php"><i class="bi bi-people"></i> Usuarios</a>
      <a href="../logout.php" class="btn btn-danger btn-sm">Salir</a>
    </div>
  </div>
</nav>

<div class="container mt-5 pt-4">

  <h4 class="text-white fw-bold mb-4">
    <i class="bi bi-speedometer2 text-warning"></i> Dashboard
  </h4>

  <!-- Tarjetas estadísticas -->
  <div class="row g-4 mb-5">
    <div class="col-md-3">
      <div class="card text-white border-0" style="background:linear-gradient(135deg,#f0c040,#e0a020);">
        <div class="card-body text-dark">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="mb-0 small fw-bold">PRODUCTOS</p>
              <h2 class="fw-bold mb-0"><?= $total_productos ?></h2>
            </div>
            <i class="bi bi-box-seam fs-1"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white border-0" style="background:linear-gradient(135deg,#4a9eff,#2070dd);">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="mb-0 small fw-bold">USUARIOS</p>
              <h2 class="fw-bold mb-0"><?= $total_usuarios ?></h2>
            </div>
            <i class="bi bi-people fs-1"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white border-0" style="background:linear-gradient(135deg,#50c878,#2ea050);">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="mb-0 small fw-bold">VENTAS</p>
              <h2 class="fw-bold mb-0"><?= $total_ventas ?></h2>
            </div>
            <i class="bi bi-receipt fs-1"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-white border-0" style="background:linear-gradient(135deg,#ff6b6b,#cc3333);">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <p class="mb-0 small fw-bold">INGRESOS</p>
              <h2 class="fw-bold mb-0">$<?= number_format($total_ingresos ?? 0, 2) ?></h2>
            </div>
            <i class="bi bi-cash-stack fs-1"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Accesos rápidos -->
  <h5 class="text-white fw-bold mb-3">Accesos Rápidos</h5>
  <div class="row g-3">
    <div class="col-md-3">
      <a href="productos.php" class="text-decoration-none">
        <div class="card bg-dark text-white text-center p-3 producto-card border-0">
          <i class="bi bi-plus-circle fs-1 text-warning"></i>
          <p class="mt-2 mb-0 fw-bold">Gestionar Productos</p>
        </div>
      </a>
    </div>
    <div class="col-md-3">
      <a href="categorias.php" class="text-decoration-none">
        <div class="card bg-dark text-white text-center p-3 producto-card border-0">
          <i class="bi bi-grid-fill fs-1 text-warning"></i>
          <p class="mt-2 mb-0 fw-bold">Gestionar Categorías</p>
        </div>
      </a>
    </div>
    <div class="col-md-3">
      <a href="ventas.php" class="text-decoration-none">
        <div class="card bg-dark text-white text-center p-3 producto-card border-0">
          <i class="bi bi-graph-up fs-1 text-warning"></i>
          <p class="mt-2 mb-0 fw-bold">Ver Ventas</p>
        </div>
      </a>
    </div>
    <div class="col-md-3">
      <a href="usuarios.php" class="text-decoration-none">
        <div class="card bg-dark text-white text-center p-3 producto-card border-0">
          <i class="bi bi-person-gear fs-1 text-warning"></i>
          <p class="mt-2 mb-0 fw-bold">Ver Usuarios</p>
        </div>
      </a>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>