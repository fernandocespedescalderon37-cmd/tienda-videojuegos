<?php
session_start();
include 'config/conexion.php';

// Traer productos destacados
$query = "SELECT p.*, c.nombre_categoria FROM producto p 
          JOIN categoria c ON p.id_categoria = c.id_categoria 
          WHERE p.estado = 1 LIMIT 8";
$resultado = mysqli_query($conexion, $query);

// Traer categorias
$query_cat = "SELECT * FROM categoria";
$categorias = mysqli_query($conexion, $query_cat);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameStore — Tu tienda de videojuegos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="navbar-main">
  <div class="container">
    <a class="navbar-brand fw-bold fs-4" href="index.php">
      <i class="bi bi-controller"></i> GameStore
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="#inicio">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="#productos">Productos</a></li>
        <li class="nav-item"><a class="nav-link" href="#categorias">Categorías</a></li>
        <li class="nav-item"><a class="nav-link" href="#nosotros">Nosotros</a></li>
      </ul>
      <div class="d-flex gap-2">
        <?php if(isset($_SESSION['id_usuario'])): ?>
          <a href="cliente/dashboard.php" class="btn btn-outline-light btn-sm">
            <i class="bi bi-person-circle"></i> Mi cuenta
          </a>
          <a href="logout.php" class="btn btn-danger btn-sm">Salir</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline-light btn-sm">
            <i class="bi bi-box-arrow-in-right"></i> Ingresar
          </a>
          <a href="registro.php" class="btn btn-warning btn-sm fw-bold">Registrarse</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<!-- HERO / BANNER -->
<section id="inicio" class="hero-section d-flex align-items-center">
  <div class="container text-center text-white">
    <h1 class="display-3 fw-bold mb-3">🎮 Bienvenido a <span class="text-warning">GameStore</span></h1>
    <p class="lead mb-4">Los mejores videojuegos y consolas al mejor precio</p>
    <a href="#productos" class="btn btn-warning btn-lg fw-bold px-5 me-2">
      <i class="bi bi-joystick"></i> Ver Juegos
    </a>
    <a href="registro.php" class="btn btn-outline-light btn-lg px-5">
      Registrarse Gratis
    </a>
  </div>
</section>

<!-- BENEFICIOS -->
<section class="py-5 bg-dark text-white">
  <div class="container">
    <div class="row text-center g-4">
      <div class="col-md-3">
        <i class="bi bi-truck fs-1 text-warning"></i>
        <h5 class="mt-2">Envío Rápido</h5>
        <p class="text-muted small">Entrega en 24-48 horas</p>
      </div>
      <div class="col-md-3">
        <i class="bi bi-shield-check fs-1 text-warning"></i>
        <h5 class="mt-2">Compra Segura</h5>
        <p class="text-muted small">Pagos 100% protegidos</p>
      </div>
      <div class="col-md-3">
        <i class="bi bi-arrow-repeat fs-1 text-warning"></i>
        <h5 class="mt-2">Devoluciones</h5>
        <p class="text-muted small">30 días sin preguntas</p>
      </div>
      <div class="col-md-3">
        <i class="bi bi-headset fs-1 text-warning"></i>
        <h5 class="mt-2">Soporte 24/7</h5>
        <p class="text-muted small">Siempre disponibles</p>
      </div>
    </div>
  </div>
</section>

<!-- CATEGORIAS -->
<section id="categorias" class="py-5 bg-darker">
  <div class="container">
    <h2 class="text-center text-white fw-bold mb-5">🗂️ Categorías</h2>
    <div class="row g-3 justify-content-center">
      <?php 
      mysqli_data_seek($categorias, 0);
      while($cat = mysqli_fetch_assoc($categorias)): ?>
      <div class="col-6 col-md-2">
        <a href="index.php?categoria=<?= $cat['id_categoria'] ?>" class="text-decoration-none">
          <div class="card bg-secondary text-white text-center p-3 categoria-card">
            <i class="bi bi-grid fs-2 text-warning"></i>
            <p class="mb-0 mt-2 small fw-bold"><?= $cat['nombre_categoria'] ?></p>
          </div>
        </a>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- PRODUCTOS DESTACADOS -->
<section id="productos" class="py-5" style="background:#1a1a2e;">
  <div class="container">
    <h2 class="text-center text-white fw-bold mb-2">🎮 Productos Destacados</h2>
    <p class="text-center text-muted mb-5">Los más vendidos de nuestra tienda</p>

    <!-- Buscador -->
    <div class="row justify-content-center mb-4">
      <div class="col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-dark border-warning">
            <i class="bi bi-search text-warning"></i>
          </span>
          <input type="text" id="buscador" class="form-control bg-dark text-white border-warning" 
                 placeholder="Buscar videojuego, consola, marca...">
        </div>
      </div>
    </div>

    <div class="row g-4" id="lista-productos">
      <?php while($prod = mysqli_fetch_assoc($resultado)): ?>
      <div class="col-6 col-md-4 col-lg-3 producto-item">
        <div class="card h-100 bg-dark text-white border-0 producto-card">
          <div class="position-relative">
            <?php if($prod['imagen'] && file_exists('assets/img/'.$prod['imagen'])): ?>
              <img src="assets/img/<?= $prod['imagen'] ?>" class="card-img-top" style="height:200px;object-fit:cover;">
            <?php else: ?>
              <div class="card-img-top d-flex align-items-center justify-content-center bg-secondary" style="height:200px;">
                <i class="bi bi-controller text-warning" style="font-size:4rem;"></i>
              </div>
            <?php endif; ?>
            <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">
              $<?= number_format($prod['precio'],2) ?>
            </span>
          </div>
          <div class="card-body d-flex flex-column">
            <span class="badge bg-secondary mb-1" style="width:fit-content;"><?= $prod['nombre_categoria'] ?></span>
            <h6 class="card-title fw-bold"><?= $prod['nombre'] ?></h6>
            <p class="card-text text-muted small flex-grow-1"><?= substr($prod['descripcion'],0,60) ?>...</p>
            <p class="text-muted small mb-2"><i class="bi bi-box"></i> Stock: <?= $prod['stock'] ?></p>
            <div class="d-grid gap-1">
            <?php if(isset($_SESSION['id_usuario'])): ?>
             <a href="cliente/agregar_carrito.php?id=<?= $prod['id_producto'] ?>" 
       class="btn btn-warning btn-sm fw-bold">
      <i class="bi bi-cart-plus"></i> Agregar
    </a>
    <a href="cliente/agregar_favorito.php?id=<?= $prod['id_producto'] ?>"
       class="btn btn-outline-danger btn-sm">
      <i class="bi bi-heart"></i> Favorito
    </a>
  <?php else: ?>
    <a href="login.php" class="btn btn-outline-warning btn-sm">
      <i class="bi bi-box-arrow-in-right"></i> Ingresar para comprar
    </a>
  <?php endif; ?>
</div>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- NOSOTROS -->
<section id="nosotros" class="py-5 bg-dark text-white">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-md-6">
        <h2 class="fw-bold mb-3">¿Por qué <span class="text-warning">GameStore</span>?</h2>
        <p class="text-muted">Somos tu tienda de confianza para videojuegos y consolas. Contamos con los últimos lanzamientos, los mejores precios y atención personalizada.</p>
        <ul class="list-unstyled mt-3">
          <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i>+500 productos disponibles</li>
          <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i>Garantía oficial en todos los productos</li>
          <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i>Comunidad gamer activa</li>
          <li class="mb-2"><i class="bi bi-check-circle-fill text-warning me-2"></i>Precios competitivos</li>
        </ul>
      </div>
      <div class="col-md-6 text-center">
        <i class="bi bi-controller text-warning" style="font-size:10rem;"></i>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="py-4 text-center text-muted" style="background:#0d0d0d;">
  <div class="container">
    <p class="mb-1">
      <i class="bi bi-controller text-warning"></i> 
      <strong class="text-white">GameStore</strong> — Tu tienda gamer de confianza
    </p>
    <p class="small mb-0">© 2025 GameStore. Todos los derechos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>