<?php
session_start();
include 'config/conexion.php';

// Si no hay sesión 2FA activa, redirigir
if (!isset($_SESSION['2fa_usuario_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$codigo_generado = $_SESSION['2fa_codigo'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo_ingresado = trim($_POST['codigo']);

    if ($codigo_ingresado == $codigo_generado) {
        // Código correcto — cargar datos del usuario
        $id  = $_SESSION['2fa_usuario_id'];
        $res = mysqli_query($conexion, "SELECT * FROM usuario WHERE id_usuario='$id'");
        $user = mysqli_fetch_assoc($res);

        // Marcar 2FA como verificado
        mysqli_query($conexion, "UPDATE usuario SET estado_2fa=1 WHERE id_usuario='$id'");

        // Crear sesión completa
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['nombre']     = $user['nombre'];
        $_SESSION['correo']     = $user['correo'];
        $_SESSION['rol']        = $user['rol'];

        // Limpiar sesión temporal
        unset($_SESSION['2fa_usuario_id']);
        unset($_SESSION['2fa_codigo']);
        unset($_SESSION['2fa_nombre']);

        // Redirigir según rol
        if ($user['rol'] == 'admin') {
            header('Location: admin/dashboard.php');
        } else {
            header('Location: cliente/dashboard.php');
        }
        exit;
    } else {
        $error = 'Código incorrecto. Intenta de nuevo.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación 2FA — GameStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card bg-dark text-white border-warning shadow-lg">
        <div class="card-body p-4 text-center">
          <i class="bi bi-shield-lock text-warning" style="font-size:3rem;"></i>
          <h3 class="fw-bold mt-2">Verificación 2FA</h3>
          <p class="text-muted small">Hola, <strong class="text-white"><?= $_SESSION['2fa_nombre'] ?></strong></p>

          <!-- Código visible en pantalla (simulación) -->
          <div class="alert alert-warning text-dark my-3">
            <i class="bi bi-key-fill"></i> Tu código de acceso es:
            <h2 class="fw-bold mb-0 mt-1 tracking-wide"><?= $codigo_generado ?></h2>
            <small>Este código expira en 5 minutos</small>
          </div>

          <?php if($error): ?>
            <div class="alert alert-danger"><i class="bi bi-x-circle"></i> <?= $error ?></div>
          <?php endif; ?>

          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Ingresa el código de 6 dígitos</label>
              <input type="text" name="codigo" 
                     class="form-control form-control-lg bg-secondary text-white border-warning text-center fw-bold"
                     placeholder="000000" maxlength="6" 
                     pattern="[0-9]{6}" autocomplete="off" required autofocus>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-warning fw-bold">
                <i class="bi bi-shield-check"></i> Verificar Código
              </button>
            </div>
          </form>

          <hr class="border-secondary mt-3">
          <a href="login.php" class="text-muted small">← Volver al login</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>