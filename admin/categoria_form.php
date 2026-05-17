<?php
session_start();
include '../includes/proteger.php';
include '../config/conexion.php';
if ($_SESSION['rol'] != 'admin') { header('Location: ../cliente/dashboard.php'); exit; }

$id        = isset($_GET['id']) ? $_GET['id'] : null;
$categoria = null;

if ($id) {
    $res       = mysqli_query($conexion, "SELECT * FROM categoria WHERE id_categoria='$id'");
    $categoria = mysqli_fetch_assoc($res);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre      = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);

    if ($id) {
        mysqli_query($conexion, "UPDATE categoria SET 
            nombre_categoria='$nombre', descripcion='$descripcion' 
            WHERE id_categoria='$id'");
    } else {
        mysqli_query($conexion, "INSERT INTO categoria (nombre_categoria, descripcion) 
            VALUES ('$nombre','$descripcion')");
    }
    header('Location: categorias.php?msg=guardado');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Editar' : 'Nueva' ?> Categoría — GameStore</title>
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
    <a href="categorias.php" class="btn btn-outline-warning btn-sm">
      <i class="bi bi-arrow-left"></i> Volver
    </a>
  </div>
</nav>

<div class="container mt-5 pt-4" style="max-width:500px;">
  <div class="card bg-dark text-white border-warning shadow">
    <div class="card-body p-4">
      <h4 class="fw-bold mb-4">
        <i class="bi bi-grid text-warning"></i>
        <?= $id ? 'Editar Categoría' : 'Nueva Categoría' ?>
      </h4>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nombre de la categoría</label>
          <input type="text" name="nombre" class="form-control bg-secondary text-white border-0"
                 value="<?= $categoria['nombre_categoria'] ?? '' ?>" required>
        </div>
        <div class="mb-4">
          <label class="form-label">Descripción</label>
          <textarea name="descripcion" rows="3"
                    class="form-control bg-secondary text-white border-0"><?= $categoria['descripcion'] ?? '' ?></textarea>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-warning fw-bold">
            <i class="bi bi-save"></i> <?= $id ? 'Actualizar' : 'Guardar' ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>