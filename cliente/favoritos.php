<?php
session_start();
include '../includes/proteger.php';
include '../config/conexion.php';

$id_usuario = $_SESSION['id_usuario'];

// Eliminar favorito
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    mysqli_query($conexion, "DELETE FROM favorito WHERE id_favorito='$id' AND id_usuario='$id_usuario'");
    header('Location: favoritos.php');
    exit;
}

$favoritos = mysqli_query($conexion,
    "SELECT f.*, p.nombre, p.precio, p.imagen, p.descripcion 
     FROM favorito f 
     JOIN producto p ON f.id_producto = p.id_producto 
     WHERE f.id_usuario='$id_usuario' 
     ORDER BY f.fecha DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favoritos — GameStore</title>
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
    <div class="d-flex gap-2">
      <a href="dashboard.php" class="btn btn-outline-warning btn-sm">Mi cuenta</a>
      <a href="../logout.php" class="btn btn-danger btn-sm">Salir</a>
    </div>
  </div>
</nav>

<div class="container mt-5 pt-4">
  <h4 class="text-white fw-bold mb-4">
    <i class="bi bi-heart text-warning"></i> Mis Favoritos
  </h4>

  <?php if(mysqli_num_rows($favoritos) == 0): ?>
    <div class="card bg-dark text-white border-warning text-center p-5">
      <i class="bi bi-heart text-warning" style="font-size:4rem;"></i>
      <h5 class="mt-3">No tienes favoritos aún</h5>
      <a href="../index.php" class="btn btn-warning mt-3">
        <i class="bi bi-shop"></i> Ver Productos
      </a>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <?php while($fav = mysqli_fetch_assoc($favoritos)): ?>
      <div class="col-md-4 col-lg-3">
        <div class="card bg-dark text-white border-0 producto-card h-100">
          <?php if($fav['imagen'] && file_exists('../assets/img/'.$fav['imagen'])): ?>
            <img src="../assets/img/<?= $fav['imagen'] ?>" class="card-img-top"
                 style="height:180px;object-fit:cover;">
          <?php else: ?>
            <div class="d-flex align-items-center justify-content-center bg-secondary"
                 style="height:180px;">
              <i class="bi bi-controller text-warning" style="font-size:3rem;"></i>
            </div>
          <?php endif; ?>
          <div class="card-body d-flex flex-column">
            <h6 class="fw-bold"><?= $fav['nombre'] ?></h6>
            <p class="text-warning fw-bold mb-2">$<?= number_format($fav['precio'],2) ?></p>
            <p class="text-muted small flex-grow-1"><?= substr($fav['descripcion'],0,60) ?>...</p>
            <div class="d-grid gap-1 mt-2">
              <a href="agregar_carrito.php?id=<?= $fav['id_producto'] ?>"
                 class="btn btn-warning btn-sm fw-bold">
                <i class="bi bi-cart-plus"></i> Agregar al carrito
              </a>
              <a href="favoritos.php?eliminar=<?= $fav['id_favorito'] ?>"
                 class="btn btn-outline-danger btn-sm"
                 onclick="return confirm('¿Quitar de favoritos?')">
                <i class="bi bi-heart-break"></i> Quitar
              </a>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>