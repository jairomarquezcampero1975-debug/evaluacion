<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";

function total($conexion, $sql) {
    return mysqli_fetch_assoc(mysqli_query($conexion, $sql))['total'];
}
$total_productos = total($conexion, "SELECT COUNT(*) total FROM producto");
$total_categorias = total($conexion, "SELECT COUNT(*) total FROM categoria");
$total_usuarios = total($conexion, "SELECT COUNT(*) total FROM usuario");
$total_ventas = total($conexion, "SELECT COUNT(*) total FROM venta");
$stock_bajo = total($conexion, "SELECT COUNT(*) total FROM producto WHERE stock <= 5 AND estado='activo'");
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Panel Administrador</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body class="admin-body">
<nav class="navbar navbar-dark admin-navbar"><div class="container"><a class="navbar-brand brand-logo fw-bold">Panel Administrador</a><a href="../logout.php" class="btn btn-danger">Cerrar sesion</a></div></nav>
<div class="container my-4">
    <h2 class="fw-bold mb-1">Bienvenido, <?php echo e($_SESSION['nombre']); ?></h2><p class="text-muted">Resumen general del sistema de venta.</p>
    <div class="row g-4 mt-2">
        <div class="col-md-3"><div class="stat-card stat-blue p-4"><h5>Productos</h5><h2><?php echo $total_productos; ?></h2><a href="productos.php" class="btn btn-light btn-sm">Gestionar</a></div></div>
        <div class="col-md-3"><div class="stat-card stat-green p-4"><h5>Categorias</h5><h2><?php echo $total_categorias; ?></h2><a href="categorias.php" class="btn btn-light btn-sm">Gestionar</a></div></div>
        <div class="col-md-3"><div class="stat-card stat-orange p-4"><h5>Usuarios</h5><h2><?php echo $total_usuarios; ?></h2><a href="usuarios.php" class="btn btn-light btn-sm">Ver</a></div></div>
        <div class="col-md-3"><div class="stat-card stat-purple p-4"><h5>Ventas</h5><h2><?php echo $total_ventas; ?></h2><a href="ventas.php" class="btn btn-light btn-sm">Ver</a></div></div>
    </div>
    <div class="row g-4 mt-3">
        <div class="col-md-4"><div class="card admin-sidebar-card"><div class="card-body"><h5>Menu rapido</h5><a href="productos.php">Productos</a><a href="categorias.php">Categorias</a><a href="stock.php">Stock</a><a href="ventas.php">Ventas</a><a href="usuarios.php">Usuarios</a></div></div></div>
        <div class="col-md-8"><div class="card"><div class="card-body"><h5>Alerta de stock</h5><p>Productos activos con stock menor o igual a 5: <strong><?php echo $stock_bajo; ?></strong></p><a href="stock.php" class="btn btn-danger">Revisar stock</a></div></div></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script></body></html>
