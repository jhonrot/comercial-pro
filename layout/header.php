<?php
$u = currentUser();
$current = basename($_SERVER['PHP_SELF']);
$menu = [
  'dashboard' => ['📊','Dashboard','dashboard.php'],
  'sales'     => ['🛒','Ventas','sales.php'],
  'products'  => ['📦','Productos','products.php'],
  'inventory' => ['📥','Inventario','inventory.php'],
  'customers' => ['👥','Clientes','customers.php'],
  'tickets'   => ['🎫','Atención','tickets.php'],
  'users'     => ['🛡️','Usuarios','users.php'],
];
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>ComercialPro</title>
<link rel="stylesheet" href="assets/style.css">
<meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="logo">🏪</div>
    <div><strong>ComercialPro</strong><div class="muted small"><?= e($u['role_label']) ?></div></div>
  </div>
  <nav class="sidebar-nav">
    <?php foreach ($menu as $mod => $m): if (!can($mod)) continue; ?>
      <a href="<?= $m[2] ?>" class="nav-item <?= $current===basename($m[2])?'active':'' ?>">
        <span class="nav-icon"><?= $m[0] ?></span><span><?= $m[1] ?></span>
      </a>
    <?php endforeach; ?>
  </nav>
  <a href="logout.php" class="nav-item logout">🚪 <span>Cerrar sesión</span></a>
</aside>

<main class="main">
  <header class="topbar">
    <button id="toggleSidebar" class="icon-btn">☰</button>
    <div class="topbar-right">
      <div class="user-chip">
        <div class="avatar"><?= strtoupper($u['name'][0]) ?></div>
        <div><strong><?= e($u['name']) ?></strong><div class="muted small"><?= e($u['role_label']) ?></div></div>
      </div>
    </div>
  </header>
  <div class="content">
    <?php if ($flash): ?>
      <div class="alert <?= $flash['type'] ?>"><?= e($flash['msg']) ?></div>
    <?php endif; ?>