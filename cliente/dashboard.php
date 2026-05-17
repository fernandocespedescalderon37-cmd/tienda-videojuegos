<?php
session_start();
include '../includes/proteger.php';
include '../config/conexion.php';

if ($_SESSION['rol'] != 'cliente') {
    header('Location: ../admin/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta — GameStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark" id="navbar-main">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">
      <i class="bi bi-controller"></i> GameStore
    </a>
    <div class="d-flex gap-2 align-items-center">
      <span class="text-warning"><i class="bi bi-person-circle"></i> <?= $_SESSION['nombre'] ?></span>
      <a href="../logout.php" class="btn btn-danger btn-sm">Salir</a>
    </div>
  </div>
</nav>

<div class="container mt-5 pt-4">
  <div class="row g-4">

    <!-- Bienvenida -->
    <div class="col-12">
      <div class="card bg-dark text-white border-warning">
        <div class="card-body">
          <h4 class="fw-bold"><i class="bi bi-controller text-warning"></i> 
          Bienvenido, <?= $_SESSION['nombre'] ?>!</h4>
          <p class="text-muted mb-0">¿Qué quieres hacer hoy?</p>
        </div>
      </div>
    </div>

    <!-- Opciones -->
    <div class="col-md-3">
      <a href="../index.php" class="text-decoration-none">
        <div class="card bg-dark text-white border-0 text-center p-3 producto-card">
          <i class="bi bi-shop fs-1 text-warning"></i>
          <h6 class="mt-2">Ver Tienda</h6>
        </div>
      </a>
    </div>
    <div class="col-md-3">
      <a href="carrito.php" class="text-decoration-none">
        <div class="card bg-dark text-white border-0 text-center p-3 producto-card">
          <i class="bi bi-cart fs-1 text-warning"></i>
          <h6 class="mt-2">Mi Carrito</h6>
        </div>
      </a>
    </div>
    <div class="col-md-3">
      <a href="favoritos.php" class="text-decoration-none">
        <div class="card bg-dark text-white border-0 text-center p-3 producto-card">
          <i class="bi bi-heart fs-1 text-warning"></i>
          <h6 class="mt-2">Favoritos</h6>
        </div>
      </a>
    </div>
    <div class="col-md-3">
      <a href="historial.php" class="text-decoration-none">
        <div class="card bg-dark text-white border-0 text-center p-3 producto-card">
          <i class="bi bi-clock-history fs-1 text-warning"></i>
          <h6 class="mt-2">Mis Compras</h6>
        </div>
      </a>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>