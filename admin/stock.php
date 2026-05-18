<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";
$productos = mysqli_query($conexion, "SELECT p.*, c.nombre_categoria FROM producto p INNER JOIN categoria c ON p.id_categoria=c.id_categoria WHERE p.stock <= 5 AND p.estado='activo' ORDER BY p.stock ASC");
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Stock bajo</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body class="admin-body">
<nav class="navbar navbar-dark admin-navbar"><div class="container"><a class="navbar-brand">Control de stock</a><a href="dashboard.php" class="btn btn-outline-light">Volver</a></div></nav>
<div class="container my-4"><h2 class="fw-bold">Productos con stock bajo</h2><div class="table-responsive"><table class="table table-hover bg-white"><tr><th>Producto</th><th>Categoria</th><th>Marca</th><th>Stock</th><th>Accion</th></tr><?php while($p=mysqli_fetch_assoc($productos)){ ?><tr><td><?php echo e($p['nombre']); ?></td><td><?php echo e($p['nombre_categoria']); ?></td><td><?php echo e($p['marca']); ?></td><td><span class="badge bg-danger"><?php echo $p['stock']; ?></span></td><td><a href="producto_editar.php?id=<?php echo $p['id_producto']; ?>" class="btn btn-warning btn-sm">Actualizar</a></td></tr><?php } ?></table></div></div>
</body></html>
