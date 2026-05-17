<?php
session_start();
include '../includes/proteger.php';
include '../config/conexion.php';
if ($_SESSION['rol'] != 'admin') { header('Location: ../cliente/dashboard.php'); exit; }

$id       = isset($_GET['id']) ? $_GET['id'] : null;
$producto = null;
$error    = '';

// Si es edición, cargar datos
if ($id) {
    $res      = mysqli_query($conexion, "SELECT * FROM producto WHERE id_producto='$id'");
    $producto = mysqli_fetch_assoc($res);
}

// Guardar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre      = trim($_POST['nombre']);
    $id_categoria= $_POST['id_categoria'];
    $marca       = trim($_POST['marca']);
    $descripcion = trim($_POST['descripcion']);
    $precio      = $_POST['precio'];
    $stock       = $_POST['stock'];
    $estado      = $_POST['estado'];
    $imagen_actual = $_POST['imagen_actual'] ?? '';

    // Subir imagen
    $imagen = $imagen_actual;
    if (!empty($_FILES['imagen']['name'])) {
        $ext      = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $imagen   = uniqid() . '.' . $ext;
        $destino  = '../assets/img/' . $imagen;
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
            $error = 'Error al subir la imagen.';
            $imagen = $imagen_actual;
        }
    }

    if (!$error) {
        if ($id) {
            // Editar
            $sql = "UPDATE producto SET 
                    nombre='$nombre', id_categoria='$id_categoria', marca='$marca',
                    descripcion='$descripcion', precio='$precio', stock='$stock',
                    imagen='$imagen', estado='$estado'
                    WHERE id_producto='$id'";
        } else {
            // Nuevo
            $sql = "INSERT INTO producto 
                    (nombre, id_categoria, marca, descripcion, precio, stock, imagen, estado)
                    VALUES ('$nombre','$id_categoria','$marca','$descripcion',
                            '$precio','$stock','$imagen','$estado')";
        }
        mysqli_query($conexion, $sql);
        header('Location: productos.php?msg=guardado');
        exit;
    }
}

$categorias = mysqli_query($conexion, "SELECT * FROM categoria");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Editar' : 'Nuevo' ?> Producto — GameStore</title>
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
    <a href="productos.php" class="btn btn-outline-warning btn-sm">
      <i class="bi bi-arrow-left"></i> Volver
    </a>
  </div>
</nav>

<div class="container mt-5 pt-4" style="max-width:700px;">
  <div class="card bg-dark text-white border-warning shadow">
    <div class="card-body p-4">
      <h4 class="fw-bold mb-4">
        <i class="bi bi-<?= $id ? 'pencil' : 'plus-circle' ?> text-warning"></i>
        <?= $id ? 'Editar Producto' : 'Nuevo Producto' ?>
      </h4>

      <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="imagen_actual" value="<?= $producto['imagen'] ?? '' ?>">

        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label">Nombre del producto</label>
            <input type="text" name="nombre" class="form-control bg-secondary text-white border-0"
                   value="<?= $producto['nombre'] ?? '' ?>" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Marca</label>
            <input type="text" name="marca" class="form-control bg-secondary text-white border-0"
                   value="<?= $producto['marca'] ?? '' ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Categoría</label>
            <select name="id_categoria" class="form-select bg-secondary text-white border-0" required>
              <option value="">Seleccionar...</option>
              <?php while($cat = mysqli_fetch_assoc($categorias)): ?>
                <option value="<?= $cat['id_categoria'] ?>"
                  <?= (isset($producto) && $producto['id_categoria'] == $cat['id_categoria']) ? 'selected' : '' ?>>
                  <?= $cat['nombre_categoria'] ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Precio ($)</label>
            <input type="number" name="precio" step="0.01" min="0"
                   class="form-control bg-secondary text-white border-0"
                   value="<?= $producto['precio'] ?? '' ?>" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" min="0"
                   class="form-control bg-secondary text-white border-0"
                   value="<?= $producto['stock'] ?? '' ?>" required>
          </div>
          <div class="col-12">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" rows="3"
                      class="form-control bg-secondary text-white border-0"><?= $producto['descripcion'] ?? '' ?></textarea>
          </div>
          <div class="col-md-8">
            <label class="form-label">Imagen</label>
            <input type="file" name="imagen" class="form-control bg-secondary text-white border-0"
                   accept="image/*">
            <?php if(!empty($producto['imagen'])): ?>
              <small class="text-muted">Imagen actual: <?= $producto['imagen'] ?></small>
            <?php endif; ?>
          </div>
          <div class="col-md-4">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select bg-secondary text-white border-0">
              <option value="1" <?= (isset($producto) && $producto['estado']==1) ? 'selected':'' ?>>Activo</option>
              <option value="0" <?= (isset($producto) && $producto['estado']==0) ? 'selected':'' ?>>Inactivo</option>
            </select>
          </div>
          <div class="col-12 d-grid mt-2">
            <button type="submit" class="btn btn-warning fw-bold">
              <i class="bi bi-save"></i> <?= $id ? 'Actualizar Producto' : 'Guardar Producto' ?>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>