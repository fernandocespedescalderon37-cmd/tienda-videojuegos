<?php
session_start();
include '../includes/proteger.php';
include '../config/conexion.php';
if ($_SESSION['rol'] != 'admin') { header('Location: ../cliente/dashboard.php'); exit; }

$ventas = mysqli_query($conexion, 
    "SELECT v.*, u.nombre, u.correo FROM venta v 
     JOIN usuario u ON v.id_usuario = u.id_usuario 
     ORDER BY v.fecha DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas — Admin GameStore</title>
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
  <h4 class="text-white fw-bold mb-4"><i class="bi bi-receipt text-warning"></i> Ventas Realizadas</h4>

  <div class="card bg-dark text-white border-0">
    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle mb-0">
        <thead style="border-bottom:2px solid #f0c040;">
          <tr>
            <th>#Venta</th>
            <th>Cliente</th>
            <th>Correo</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Detalle</th>
          </tr>
        </thead>
        <tbody>
          <?php while($v = mysqli_fetch_assoc($ventas)): ?>
          <tr>
            <td><span class="badge bg-warning text-dark">#<?= $v['id_venta'] ?></span></td>
            <td class="fw-bold"><?= $v['nombre'] ?></td>
            <td class="text-muted small"><?= $v['correo'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
            <td class="text-warning fw-bold">$<?= number_format($v['total'],2) ?></td>
            <td>
              <span class="badge 
                <?= $v['estado_venta']=='completada' ? 'bg-success' : 
                   ($v['estado_venta']=='cancelada' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                <?= ucfirst($v['estado_venta']) ?>
              </span>
            </td>
            <td>
              <a href="detalle_venta.php?id=<?= $v['id_venta'] ?>" class="btn btn-sm btn-outline-warning">
                <i class="bi bi-eye"></i> Ver
              </a>
            </td>
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