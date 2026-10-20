<?php
require 'helpers.php';
requirePermission('dashboard');
$u = currentUser();

$stats = [
  'productos' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
  'stock_bajo'=> $pdo->query("SELECT COUNT(*) FROM products WHERE stock <= min_stock")->fetchColumn(),
  'ventas_hoy'=> $pdo->query("SELECT COALESCE(SUM(total),0) FROM sales WHERE DATE(created_at)=CURDATE()")->fetchColumn(),
  'tickets_ab'=> $pdo->query("SELECT COUNT(*) FROM tickets WHERE status<>'cerrado'")->fetchColumn(),
  'clientes'  => $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn(),
];
$ultimasVentas = $pdo->query("SELECT s.*, u.name AS vendedor FROM sales s
    JOIN users u ON u.id=s.user_id ORDER BY s.id DESC LIMIT 5")->fetchAll();
?>
<?php include 'layout/header.php'; ?>
<h1 class="page-title">Panel de control</h1>
<p class="muted">Hola <?= e($u['name']) ?>, este es el resumen de hoy.</p>

<div class="stats-grid">
  <div class="stat-card blue">
    <div class="stat-icon">📦</div>
    <div><div class="stat-label">Productos</div><div class="stat-value"><?= $stats['productos'] ?></div></div>
  </div>
  <div class="stat-card red">
    <div class="stat-icon">⚠️</div>
    <div><div class="stat-label">Stock bajo</div><div class="stat-value"><?= $stats['stock_bajo'] ?></div></div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon">💰</div>
    <div><div class="stat-label">Ventas hoy</div><div class="stat-value"><?= money($stats['ventas_hoy']) ?></div></div>
  </div>
  <div class="stat-card orange">
    <div class="stat-icon">🎫</div>
    <div><div class="stat-label">Tickets abiertos</div><div class="stat-value"><?= $stats['tickets_ab'] ?></div></div>
  </div>
  <div class="stat-card purple">
    <div class="stat-icon">👥</div>
    <div><div class="stat-label">Clientes</div><div class="stat-value"><?= $stats['clientes'] ?></div></div>
  </div>
</div>

<div class="grid-2">
  <div class="card">
    <h3>Últimas ventas</h3>
    <table class="table">
      <thead><tr><th>Factura</th><th>Vendedor</th><th>Total</th><th>Fecha</th></tr></thead>
      <tbody>
      <?php foreach ($ultimasVentas as $v): ?>
        <tr>
          <td><strong><?= e($v['invoice']) ?></strong></td>
          <td><?= e($v['vendedor']) ?></td>
          <td><?= money($v['total']) ?></td>
          <td><?= date('d/m H:i', strtotime($v['created_at'])) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$ultimasVentas): ?>
        <tr><td colspan="4" class="muted">Sin ventas aún</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
  <div class="card">
    <h3>Accesos rápidos</h3>
    <div class="quick-actions">
      <?php if (can('sales')): ?><a href="sales.php?action=new" class="action-btn">🛒 Nueva venta</a><?php endif; ?>
      <?php if (can('products')): ?><a href="products.php?action=new" class="action-btn">➕ Nuevo producto</a><?php endif; ?>
      <?php if (can('inventory')): ?><a href="inventory.php" class="action-btn">📥 Movimiento stock</a><?php endif; ?>
      <?php if (can('tickets')): ?><a href="tickets.php?action=new" class="action-btn">🎫 Nuevo ticket</a><?php endif; ?>
      <?php if (can('customers')): ?><a href="customers.php?action=new" class="action-btn">👤 Nuevo cliente</a><?php endif; ?>
    </div>
  </div>
</div>
<?php include 'layout/footer.php'; ?>