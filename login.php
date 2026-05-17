<?php
session_start();
include 'config/conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo   = trim($_POST['correo']);
    $password = $_POST['password'];

    if (empty($correo) || empty($password)) {
        $error = 'Completa todos los campos.';
    } else {
        $sql  = "SELECT * FROM usuario WHERE correo='$correo'";
        $res  = mysqli_query($conexion, $sql);
        $user = mysqli_fetch_assoc($res);

        if ($user && password_verify($password, $user['contrasena'])) {
            // Generar código 2FA
            $codigo = rand(100000, 999999);
            $id     = $user['id_usuario'];
            mysqli_query($conexion, "UPDATE usuario SET codigo_2fa='$codigo', estado_2fa=0 WHERE id_usuario='$id'");

            // Guardar en sesión temporalmente
            $_SESSION['2fa_usuario_id'] = $id;
            $_SESSION['2fa_codigo']     = $codigo;
            $_SESSION['2fa_nombre']     = $user['nombre'];

            header('Location: verificar_2fa.php');
            exit;
        } else {
            $error = 'Correo o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — GameStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card bg-dark text-white border-warning shadow-lg">
        <div class="card-body p-4">
          <div class="text-center mb-4">
            <i class="bi bi-controller text-warning" style="font-size:3rem;"></i>
            <h3 class="fw-bold mt-2">Iniciar Sesión</h3>
            <p class="text-muted small">Bienvenido de vuelta</p>
          </div>

          <?php if($error): ?>
            <div class="alert alert-danger"><i class="bi bi-x-circle"></i> <?= $error ?></div>
          <?php endif; ?>

          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Correo electrónico</label>
              <div class="input-group">
                <span class="input-group-text bg-secondary border-0">
                  <i class="bi bi-envelope text-warning"></i>
                </span>
                <input type="email" name="correo" class="form-control bg-secondary text-white border-0"
                       placeholder="correo@ejemplo.com" required>
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text bg-secondary border-0">
                  <i class="bi bi-lock text-warning"></i>
                </span>
                <input type="password" name="password" id="pass"
                       class="form-control bg-secondary text-white border-0"
                       placeholder="Tu contraseña" required>
                <button class="btn btn-secondary border-0" type="button" onclick="verPass()">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-warning fw-bold">
                <i class="bi bi-box-arrow-in-right"></i> Ingresar
              </button>
            </div>
          </form>

          <hr class="border-secondary mt-4">
          <p class="text-center text-muted small mb-1">
            ¿No tienes cuenta? <a href="registro.php" class="text-warning">Regístrate gratis</a>
          </p>
          <p class="text-center">
            <a href="index.php" class="text-muted small">← Volver al inicio</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function verPass() {
  const input = document.getElementById('pass');
  input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>