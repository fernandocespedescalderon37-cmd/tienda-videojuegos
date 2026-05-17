<?php
session_start();
include 'config/conexion.php';

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre   = trim($_POST['nombre']);
    $correo   = trim($_POST['correo']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirmar'];

    if (empty($nombre) || empty($correo) || empty($password)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif ($password !== $confirm) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        // Verificar si el correo ya existe
        $check = mysqli_query($conexion, "SELECT id_usuario FROM usuario WHERE correo='$correo'");
        if (mysqli_num_rows($check) > 0) {
            $error = 'Este correo ya está registrado.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql  = "INSERT INTO usuario (nombre, correo, contrasena, rol) 
                     VALUES ('$nombre', '$correo', '$hash', 'cliente')";
            if (mysqli_query($conexion, $sql)) {
                $exito = '¡Cuenta creada! Ya puedes iniciar sesión.';
            } else {
                $error = 'Error al registrar. Intenta de nuevo.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro — GameStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card bg-dark text-white border-warning shadow-lg">
        <div class="card-body p-4">
          <div class="text-center mb-4">
            <i class="bi bi-controller text-warning" style="font-size:3rem;"></i>
            <h3 class="fw-bold mt-2">Crear Cuenta</h3>
            <p class="text-muted small">Únete a GameStore</p>
          </div>

          <?php if($error): ?>
            <div class="alert alert-danger"><i class="bi bi-x-circle"></i> <?= $error ?></div>
          <?php endif; ?>
          <?php if($exito): ?>
            <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?= $exito ?></div>
          <?php endif; ?>

          <form method="POST" id="formRegistro">
            <div class="mb-3">
              <label class="form-label">Nombre completo</label>
              <div class="input-group">
                <span class="input-group-text bg-secondary border-0"><i class="bi bi-person text-warning"></i></span>
                <input type="text" name="nombre" class="form-control bg-secondary text-white border-0" 
                       placeholder="Tu nombre" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Correo electrónico</label>
              <div class="input-group">
                <span class="input-group-text bg-secondary border-0"><i class="bi bi-envelope text-warning"></i></span>
                <input type="email" name="correo" class="form-control bg-secondary text-white border-0" 
                       placeholder="correo@ejemplo.com" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text bg-secondary border-0"><i class="bi bi-lock text-warning"></i></span>
                <input type="password" name="password" id="pass" 
                       class="form-control bg-secondary text-white border-0" 
                       placeholder="Mínimo 6 caracteres" required>
                <button class="btn btn-secondary border-0" type="button" onclick="verPass('pass')">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label">Confirmar contraseña</label>
              <div class="input-group">
                <span class="input-group-text bg-secondary border-0"><i class="bi bi-lock-fill text-warning"></i></span>
                <input type="password" name="confirmar" id="pass2" 
                       class="form-control bg-secondary text-white border-0" 
                       placeholder="Repite tu contraseña" required>
                <button class="btn btn-secondary border-0" type="button" onclick="verPass('pass2')">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-warning fw-bold">
                <i class="bi bi-person-plus"></i> Crear Cuenta
              </button>
            </div>
          </form>

          <hr class="border-secondary mt-4">
          <p class="text-center text-muted small mb-0">
            ¿Ya tienes cuenta? <a href="login.php" class="text-warning">Inicia sesión</a>
          </p>
          <p class="text-center mt-2">
            <a href="index.php" class="text-muted small">← Volver al inicio</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function verPass(id) {
  const input = document.getElementById(id);
  input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>