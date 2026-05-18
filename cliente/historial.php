<?php
require_once "../includes/auth.php";
protegerRuta();
soloCliente();
require_once "../config/conexion.php";
$id_usuario = $_SESSION['id_usuario'];
$stmt = mysqli_prepare($conexion, "SELECT * FROM venta WHERE id_usuario=? ORDER BY id_venta DESC");
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$ventas = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Historial</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body>
<nav class="navbar navbar-dark nav-glass"><div class="container"><a class="navbar-brand brand-logo">Historial de compras</a><a href="inicio.php" class="btn btn-outline-light">Volver</a></div></nav>
<div class="container my-5"><h2 class="fw-bold">Mis compras</h2><?php if(isset($_GET['compra'])){ ?><div class="alert alert-success">Compra registrada correctamente.</div><?php } ?><div class="table-responsive"><table class="table table-hover bg-white"><tr><th>ID</th><th>Fecha</th><th>Total</th><th>Estado</th></tr><?php while($v=mysqli_fetch_assoc($ventas)){ ?><tr><td><?php echo $v['id_venta']; ?></td><td><?php echo e($v['fecha']); ?></td><td>Bs <?php echo number_format($v['total'],2); ?></td><td><span class="badge bg-info"><?php echo e($v['estado_venta']); ?></span></td></tr><?php } ?></table></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script></body></html>
