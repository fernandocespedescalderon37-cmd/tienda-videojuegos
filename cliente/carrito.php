<?php
session_start();
include '../includes/proteger.php';
include '../config/conexion.php';

$id_usuario = $_SESSION['id_usuario'];

// Eliminar producto del carrito
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    mysqli_query($conexion, "DELETE FROM carrito WHERE id_carrito='$id' AND id_usuario='$id_usuario'");
    header('Location: carrito.php');
    exit;
}

// Actualizar cantidad
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar'])) {
    foreach ($_POST['cantidad'] as $id_carrito => $cantidad) {
        $cantidad = max(1, intval($cantidad));
        mysqli_query($conexion, 
            "UPDATE carrito SET cantidad='$cantidad' WHERE id_carrito='$id_carrito' AND id_usuario='$id_usuario'");
    }
    header('Location: carrito.php');
    exit;
}

// Traer items del carrito
$sql = "SELECT c.*, p.nombre, p.precio, p.imagen, p.stock 
        FROM carrito c 
        JOIN producto p ON c.id_producto = p.id_producto 
        WHERE c.id_usuario='$id_usuario'";
$items    = mysqli_query($conexion, $sql);
$total    = 0;
$carrito  = [];

while ($item = mysqli_fetch_assoc($items)) {
    $item['subtotal'] = $item['precio'] * $item['cantidad'];
    $total += $item['subtotal'];
    $carrito[] = $item;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito — GameStore</title>
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
    <div class="d-flex gap-2 align-items-center">
      <a href="dashboard.php" class="btn btn-outline-warning btn-sm">
        <i class="bi bi-person-circle"></i> Mi cuenta
      </a>
      <a href="../logout.php" class="btn btn-danger btn-sm">Salir</a>
    </div>
  </div>
</nav>

<div class="container mt-5 pt-4">
  <h4 class="text-white fw-bold mb-4">
    <i class="bi bi-cart text-warning"></i> Mi Carrito
  </h4>

  <?php if(empty($carrito)): ?>
    <div class="card bg-dark text-white border-warning text-center p-5">
      <i class="bi bi-cart-x text-warning" style="font-size:4rem;"></i>
      <h5 class="mt-3">Tu carrito está vacío</h5>
      <a href="../index.php" class="btn btn-warning mt-3">
        <i class="bi bi-shop"></i> Ver Productos
      </a>
    </div>
  <?php else: ?>
    <form method="POST">
      <div class="row g-4">
        <div class="col-lg-8">
          <div class="card bg-dark text-white border-0">
            <div class="card-body p-0">
              <?php foreach($carrito as $item): ?>
              <div class="d-flex align-items-center p-3 border-bottom border-secondary">
                <!-- Imagen -->
                <div class="me-3">
                  <?php if($item['imagen'] && file_exists('../assets/img/'.$item['imagen'])): ?>
                    <img src="../assets/img/<?= $item['imagen'] ?>" width="70" height="70"
                         style="object-fit:cover;border-radius:8px;">
                  <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center bg-secondary rounded"
                         style="width:70px;height:70px;">
                      <i class="bi bi-controller text-warning fs-3"></i>
                    </div>
                  <?php endif; ?>
                </div>
                <!-- Info -->
                <div class="flex-grow-1">
                  <h6 class="fw-bold mb-1"><?= $item['nombre'] ?></h6>
                  <p class="text-warning mb-0 fw-bold">$<?= number_format($item['precio'],2) ?></p>
                </div>
                <!-- Cantidad -->
                <div class="mx-3" style="width:80px;">
                  <input type="number" name="cantidad[<?= $item['id_carrito'] ?>]"
                         value="<?= $item['cantidad'] ?>" min="1" max="<?= $item['stock'] ?>"
                         class="form-control form-control-sm bg-secondary text-white border-warning text-center">
                </div>
                <!-- Subtotal -->
                <div class="me-3 text-end" style="min-width:80px;">
                  <span class="text-warning fw-bold">$<?= number_format($item['subtotal'],2) ?></span>
                </div>
                <!-- Eliminar -->
                <a href="carrito.php?eliminar=<?= $item['id_carrito'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('¿Quitar este producto?')">
                  <i class="bi bi-trash"></i>
                </a>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <button type="submit" name="actualizar" class="btn btn-outline-warning mt-3">
            <i class="bi bi-arrow-repeat"></i> Actualizar cantidades
          </button>
        </div>

        <!-- Resumen -->
        <div class="col-lg-4">
          <div class="card bg-dark text-white border-warning">
            <div class="card-body">
              <h5 class="fw-bold mb-3">Resumen del pedido</h5>
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Subtotal:</span>
                <span>$<?= number_format($total,2) ?></span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Envío:</span>
                <span class="text-success">Gratis</span>
              </div>
              <hr class="border-warning">
              <div class="d-flex justify-content-between mb-4">
                <span class="fw-bold fs-5">Total:</span>
                <span class="fw-bold fs-5 text-warning">$<?= number_format($total,2) ?></span>
              </div>
              <a href="confirmar_compra.php" class="btn btn-warning w-100 fw-bold">
                <i class="bi bi-bag-check"></i> Confirmar Compra
              </a>
              <a href="../index.php" class="btn btn-outline-secondary w-100 mt-2">
                <i class="bi bi-arrow-left"></i> Seguir comprando
              </a>
            </div>
          </div>
        </div>
      </div>
    </form>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>