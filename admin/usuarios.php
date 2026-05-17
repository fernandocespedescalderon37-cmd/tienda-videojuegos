<?php
session_start();
include '../includes/proteger.php';
include '../config/conexion.php';
if ($_SESSION['rol'] != 'admin') { header('Location: ../cliente/dashboard.php'); exit; }

$usuarios = mysqli_query($conexion, "SELECT * FROM usuario ORDER BY fecha_registro DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios — Admin GameStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark" id="navbar-main">
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
  <h4 class="text-white fw-bold mb-4"><i class="bi bi-people text-warning"></i> Usuarios Registrados</h4>

  <div class="card bg-dark text-white border-0">
    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle mb-0">
        <thead style="border-bottom:2px solid #f0c040;">
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Fecha Registro</th>
          </tr>
        </thead>
        <tbody>
          <?php while($u = mysqli_fetch_assoc($usuarios)): ?>
          <tr>
            <td><?= $u['id_usuario'] ?></td>
            <td class="fw-bold"><?= $u['nombre'] ?></td>
            <td class="text-muted"><?= $u['correo'] ?></td>
            <td>
              <span class="badge <?= $u['rol']=='admin' ? 'bg-warning text-dark' : 'bg-primary' ?>">
                <?= ucfirst($u['rol']) ?>
              </span>
            </td>
            <td><?= date('d/m/Y', strtotime($u['fecha_registro'])) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>