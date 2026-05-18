<?php
require_once "../includes/auth.php";
protegerRuta();
soloCliente();
require_once "../config/conexion.php";
$id_usuario = $_SESSION['id_usuario'];
$stmt = mysqli_prepare($conexion, "SELECT p.*, c.nombre_categoria FROM favorito f INNER JOIN producto p ON f.id_producto=p.id_producto INNER JOIN categoria c ON p.id_categoria=c.id_categoria WHERE f.id_usuario=? ORDER BY f.fecha DESC");
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$favoritos = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Favoritos</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body>
<nav class="navbar navbar-dark nav-glass"><div class="container"><a class="navbar-brand brand-logo">Favoritos</a><div><a href="inicio.php" class="btn btn-outline-light">Volver</a> <a href="../logout.php" class="btn btn-danger">Cerrar sesion</a></div></div></nav>
<div class="container my-5"><h2 class="fw-bold">Mis favoritos</h2><div class="row g-4"><?php while($p=mysqli_fetch_assoc($favoritos)){ ?><div class="col-md-4"><div class="card h-100 producto-card"><img class="product-img" src="<?php echo e(imagenProducto($p['imagen'], '../')); ?>"><div class="card-body d-flex flex-column"><span class="badge bg-primary align-self-start mb-3"><?php echo e($p['nombre_categoria']); ?></span><h5><?php echo e($p['nombre']); ?></h5><p class="flex-grow-1"><?php echo e($p['descripcion']); ?></p><h4 class="text-primary">Bs <?php echo number_format($p['precio'],2); ?></h4><div class="d-grid gap-2 mt-auto"><a href="agregar_carrito.php?id=<?php echo $p['id_producto']; ?>" class="btn btn-dark">Agregar carrito</a><a href="eliminar_favorito.php?id=<?php echo $p['id_producto']; ?>" class="btn btn-outline-danger">Eliminar favorito</a></div></div></div></div><?php } ?></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script></body></html>
