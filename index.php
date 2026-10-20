<?php
require 'helpers.php';
if (currentUser()) { header('Location: dashboard.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT u.*, r.name AS role, r.label AS role_label
                           FROM users u JOIN roles r ON r.id=u.role_id
                           WHERE u.email=? AND u.active=1");
    $stmt->execute([$email]);
    $u = $stmt->fetch();
    if ($u && password_verify($pass, $u['password'])) {
        $_SESSION['user'] = $u;
        header('Location: dashboard.php'); exit;
    }
    $error = 'Credenciales inválidas o usuario inactivo';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ingreso · ComercialPro</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="auth-body">
  <div class="auth-wrap">
    <div class="auth-brand">
      <div class="logo">🏪</div>
      <h1>ComercialPro</h1>
      <p>Sistema integral de gestión comercial</p>
    </div>
    <form method="POST" class="auth-card">
      <h2>Bienvenido</h2>
      <p class="muted">Ingresa con tu cuenta</p>
      <label>Email<input type="email" name="email" required placeholder="admin@comercial.com"></label>
      <label>Contraseña<input type="password" name="password" required placeholder="••••••••"></label>
      <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
      <button class="btn primary block">Iniciar sesión</button>
      <p class="muted small" style="margin-top:1rem;text-align:center">
        Demo: <code>admin@comercial.com</code> / <code>admin123</code>
      </p>
    </form>
  </div>
</body>
</html>