<?php
session_start();
include '../includes/proteger.php';
include '../config/conexion.php';
if ($_SESSION['rol'] != 'admin') { header('Location: ../cliente/dashboard.php'); exit; }

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    mysqli_query($conexion, "DELETE FROM categoria WHERE id_categoria='$id'");
    header('Location: categorias.php?msg=eliminado');
    exit;
}

$categorias = mysqli_query($conexion, "SELECT * FROM categoria ORDER BY id_categoria DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías — Admin GameStore</title>
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
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-white fw-bold"><i class="bi bi-grid text-warning"></i> Categorías</h4>
    <a href="categoria_form.php" class="btn btn-warning fw-bold">
      <i class="bi bi-plus-circle"></i> Nueva Categoría
    </a>
  </div>

  <?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-success">
      <?= $_GET['msg'] == 'guardado' ? '✅ Categoría guardada.' : '🗑️ Categoría eliminada.' ?>
    </div>
  <?php endif; ?>

  <div class="card bg-dark text-white border-0">
    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle mb-0">
        <thead style="border-bottom:2px solid #f0c040;">
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while($cat = mysqli_fetch_assoc($categorias)): ?>
          <tr>
            <td><?= $cat['id_categoria'] ?></td>
            <td class="fw-bold"><?= $cat['nombre_categoria'] ?></td>
            <td class="text-muted"><?= $cat['descripcion'] ?></td>
            <td>
              <a href="categoria_form.php?id=<?= $cat['id_categoria'] ?>" 
                 class="btn btn-warning btn-sm">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="categorias.php?eliminar=<?= $cat['id_categoria'] ?>" 
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('¿Eliminar esta categoría?')">
                <i class="bi bi-trash"></i>
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