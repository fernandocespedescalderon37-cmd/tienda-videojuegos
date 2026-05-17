<?php
session_start();
include '../includes/proteger.php';
include '../config/conexion.php';
if ($_SESSION['rol'] != 'admin') { header('Location: ../cliente/dashboard.php'); exit; }

// Eliminar producto
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    mysqli_query($conexion, "DELETE FROM producto WHERE id_producto='$id'");
    header('Location: productos.php?msg=eliminado');
    exit;
}

// Buscar
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$where  = $buscar ? "WHERE p.nombre LIKE '%$buscar%' OR p.marca LIKE '%$buscar%'" : '';

$sql = "SELECT p.*, c.nombre_categoria FROM producto p 
        LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
        $where ORDER BY p.id_producto DESC";
$productos = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos — Admin GameStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
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
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-white fw-bold"><i class="bi bi-box text-warning"></i> Productos</h4>
    <a href="producto_form.php" class="btn btn-warning fw-bold">
      <i class="bi bi-plus-circle"></i> Nuevo Producto
    </a>
  </div>

  <?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-success">
      <?= $_GET['msg'] == 'guardado' ? '✅ Producto guardado correctamente.' : '🗑️ Producto eliminado.' ?>
    </div>
  <?php endif; ?>

  <!-- Buscador -->
  <form method="GET" class="mb-4">
    <div class="input-group" style="max-width:400px;">
      <input type="text" name="buscar" class="form-control bg-dark text-white border-warning"
             placeholder="Buscar por nombre o marca..." value="<?= $buscar ?>">
      <button class="btn btn-warning" type="submit"><i class="bi bi-search"></i></button>
      <?php if($buscar): ?>
        <a href="productos.php" class="btn btn-secondary">Limpiar</a>
      <?php endif; ?>
    </div>
  </form>

  <div class="card bg-dark text-white border-0">
    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle mb-0">
        <thead style="border-bottom:2px solid #f0c040;">
          <tr>
            <th>#</th>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Marca</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while($p = mysqli_fetch_assoc($productos)): ?>
          <tr>
            <td><?= $p['id_producto'] ?></td>
            <td>
              <?php if($p['imagen'] && file_exists('../assets/img/'.$p['imagen'])): ?>
                <img src="../assets/img/<?= $p['imagen'] ?>" width="50" height="50" 
                     style="object-fit:cover;border-radius:8px;">
              <?php else: ?>
                <i class="bi bi-controller text-warning fs-3"></i>
              <?php endif; ?>
            </td>
            <td class="fw-bold"><?= $p['nombre'] ?></td>
            <td><span class="badge bg-secondary"><?= $p['nombre_categoria'] ?></span></td>
            <td><?= $p['marca'] ?></td>
            <td class="text-warning fw-bold">$<?= number_format($p['precio'],2) ?></td>
            <td>
              <span class="badge <?= $p['stock'] > 5 ? 'bg-success' : 'bg-danger' ?>">
                <?= $p['stock'] ?>
              </span>
            </td>
            <td>
              <span class="badge <?= $p['estado'] ? 'bg-success' : 'bg-secondary' ?>">
                <?= $p['estado'] ? 'Activo' : 'Inactivo' ?>
              </span>
            </td>
            <td>
              <a href="producto_form.php?id=<?= $p['id_producto'] ?>" 
                 class="btn btn-warning btn-sm">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="productos.php?eliminar=<?= $p['id_producto'] ?>" 
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('¿Eliminar este producto?')">
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