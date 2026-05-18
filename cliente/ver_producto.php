<?php
require_once "../includes/auth.php";
protegerRuta();
soloCliente();
require_once "../config/conexion.php";
$id = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conexion, "SELECT p.*, c.nombre_categoria FROM producto p INNER JOIN categoria c ON p.id_categoria=c.id_categoria WHERE p.id_producto=? AND p.estado='activo'");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$p = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$p) { header("Location: inicio.php"); exit(); }
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title><?php echo e($p['nombre']); ?></title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body>
<nav class="navbar navbar-dark nav-glass"><div class="container"><a class="navbar-brand brand-logo">Detalle producto</a><a href="inicio.php" class="btn btn-outline-light">Volver</a></div></nav>
<div class="container my-5"><div class="card"><div class="card-body"><div class="row g-4 align-items-center"><div class="col-md-5"><img class="product-img-lg" src="<?php echo e(imagenProducto($p['imagen'], '../')); ?>"></div><div class="col-md-7"><span class="badge bg-primary mb-3"><?php echo e($p['nombre_categoria']); ?></span><h1 class="fw-bold"><?php echo e($p['nombre']); ?></h1><p class="lead text-muted"><?php echo e($p['descripcion']); ?></p><p><strong>Marca:</strong> <?php echo e($p['marca']); ?></p><p><strong>Stock:</strong> <?php echo $p['stock']; ?></p><h2 class="text-primary">Bs <?php echo number_format($p['precio'],2); ?></h2><div class="d-flex flex-wrap gap-2 mt-4"><a href="agregar_carrito.php?id=<?php echo $p['id_producto']; ?>" class="btn btn-dark">Agregar carrito</a><a href="agregar_favorito.php?id=<?php echo $p['id_producto']; ?>" class="btn btn-outline-danger">Agregar favorito</a></div></div></div></div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script></body></html>
