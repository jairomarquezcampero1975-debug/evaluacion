<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";
function total($conexion, $sql) { return mysqli_fetch_assoc(mysqli_query($conexion, $sql))['total']; }
$total_productos = total($conexion, "SELECT COUNT(*) total FROM producto");
$total_categorias = total($conexion, "SELECT COUNT(*) total FROM categoria");
$total_usuarios = total($conexion, "SELECT COUNT(*) total FROM usuario");
$total_ventas = total($conexion, "SELECT COUNT(*) total FROM venta");
$stock_bajo = total($conexion, "SELECT COUNT(*) total FROM producto WHERE stock <= 5 AND estado='activo'");
$ingresos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT IFNULL(SUM(total),0) total FROM venta WHERE estado_venta<>'cancelado'"))['total'];
$ultimas = mysqli_query($conexion, "SELECT v.*, u.nombre FROM venta v INNER JOIN usuario u ON v.id_usuario=u.id_usuario ORDER BY v.id_venta DESC LIMIT 5");
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Panel Administrador</title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body class="admin-body"><nav class="navbar navbar-dark admin-navbar sticky-top"><div class="container-fluid px-4"><a class="navbar-brand brand-logo" href="dashboard.php">PapuStore Admin</a><div class="d-flex gap-2"><a href="../index.php" class="btn btn-outline-light btn-sm"><i class="bi bi-house"></i> Tienda</a><a href="../logout.php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</a></div></div></nav><div class="admin-shell"><aside class="admin-sidebar"><div class="menu-title">Panel</div><a class="admin-link active" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a><a class="admin-link " href="productos.php"><i class="bi bi-phone"></i> Productos</a><a class="admin-link " href="categorias.php"><i class="bi bi-tags"></i> Categorias</a><a class="admin-link " href="stock.php"><i class="bi bi-box-seam"></i> Stock</a><a class="admin-link " href="ventas.php"><i class="bi bi-receipt"></i> Ventas</a><a class="admin-link " href="usuarios.php"><i class="bi bi-people"></i> Usuarios</a><div class="menu-title">Seguridad</div><div class="mini-badge"><i class="bi bi-shield-check"></i> Rutas protegidas</div><div class="mini-badge"><i class="bi bi-lock"></i> Rol admin</div></aside><main class="admin-content">
<section class="page-hero">
    <div class="row align-items-center g-4">
        <div class="col-lg-8">
            <span class="mini-badge"><i class="bi bi-stars"></i> Administracion profesional</span>
            <h1 class="fw-bold display-5 mb-2">Bienvenido, <?php echo e($_SESSION['nombre']); ?></h1>
            <p class="mb-0 text-white-50">Control general del sistema: ventas, stock, clientes, categorias y catalogo.</p>
        </div>
        <div class="col-lg-4 text-lg-end"><a href="productos.php" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> Nuevo producto</a></div>
    </div>
</section>
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3"><div class="metric-card metric-blue"><span>Productos</span><h2><?php echo $total_productos; ?></h2><a class="btn btn-outline-light btn-sm align-self-start" href="productos.php">Gestionar</a></div></div>
    <div class="col-md-6 col-xl-3"><div class="metric-card metric-green"><span>Categorias</span><h2><?php echo $total_categorias; ?></h2><a class="btn btn-outline-light btn-sm align-self-start" href="categorias.php">Gestionar</a></div></div>
    <div class="col-md-6 col-xl-3"><div class="metric-card metric-purple"><span>Usuarios</span><h2><?php echo $total_usuarios; ?></h2><a class="btn btn-outline-light btn-sm align-self-start" href="usuarios.php">Ver usuarios</a></div></div>
    <div class="col-md-6 col-xl-3"><div class="metric-card metric-orange"><span>Ventas</span><h2><?php echo $total_ventas; ?></h2><a class="btn btn-outline-light btn-sm align-self-start" href="ventas.php">Ver ventas</a></div></div>
</div>
<div class="row g-4">
    <div class="col-lg-4"><div class="card h-100"><div class="card-body"><div class="feature-icon"><i class="bi bi-cash-coin"></i></div><h4>Ingresos registrados</h4><div class="price mb-2">Bs <?php echo number_format($ingresos,2); ?></div><p>Total de ventas no canceladas.</p></div></div></div>
    <div class="col-lg-4"><div class="card h-100"><div class="card-body"><div class="feature-icon"><i class="bi bi-exclamation-triangle"></i></div><h4>Stock bajo</h4><div class="price mb-2"><?php echo $stock_bajo; ?> productos</div><p>Productos activos con 5 unidades o menos.</p><a href="stock.php" class="btn btn-danger">Revisar stock</a></div></div></div>
    <div class="col-lg-4"><div class="card h-100"><div class="card-body"><div class="feature-icon"><i class="bi bi-shield-lock"></i></div><h4>Seguridad activa</h4><p>Rutas protegidas, roles separados, sesiones PHP y verificacion 2FA.</p><div class="d-grid gap-2"><span class="badge bg-success">Admin protegido</span><span class="badge bg-primary">Cliente separado</span></div></div></div></div>
</div>
<div class="card mt-4"><div class="card-body"><h4 class="mb-3">Ultimas ventas</h4><div class="table-responsive"><table class="table table-hover align-middle"><tr><th>ID</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Estado</th></tr><?php while($v=mysqli_fetch_assoc($ultimas)){ ?><tr><td>#<?php echo $v['id_venta']; ?></td><td><?php echo e($v['nombre']); ?></td><td><?php echo e($v['fecha']); ?></td><td>Bs <?php echo number_format($v['total'],2); ?></td><td><span class="badge bg-info"><?php echo e($v['estado_venta']); ?></span></td></tr><?php } ?></table></div></div></div>
</main></div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script></body></html>