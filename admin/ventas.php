<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";
$ventas = mysqli_query($conexion, "SELECT v.*, u.nombre, u.correo FROM venta v INNER JOIN usuario u ON v.id_usuario=u.id_usuario ORDER BY v.id_venta DESC");
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Ventas</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body class="admin-body">
<nav class="navbar navbar-dark admin-navbar"><div class="container"><a class="navbar-brand">Ventas</a><a href="dashboard.php" class="btn btn-outline-light">Volver</a></div></nav>
<div class="container my-4"><h2 class="fw-bold">Registro de ventas</h2><div class="table-responsive"><table class="table table-hover bg-white"><tr><th>ID</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Estado</th><th>Cambiar estado</th></tr><?php while($v=mysqli_fetch_assoc($ventas)){ ?><tr><td><?php echo $v['id_venta']; ?></td><td><?php echo e($v['nombre']); ?><br><small class="text-muted"><?php echo e($v['correo']); ?></small></td><td><?php echo e($v['fecha']); ?></td><td>Bs <?php echo number_format($v['total'],2); ?></td><td><span class="badge bg-info"><?php echo e($v['estado_venta']); ?></span></td><td><form method="POST" action="cambiar_estado_venta.php" class="d-flex gap-2"><input type="hidden" name="id_venta" value="<?php echo $v['id_venta']; ?>"><select name="estado_venta" class="form-select form-select-sm"><option value="pendiente" <?php echo $v['estado_venta']==='pendiente'?'selected':''; ?>>pendiente</option><option value="pagado" <?php echo $v['estado_venta']==='pagado'?'selected':''; ?>>pagado</option><option value="cancelado" <?php echo $v['estado_venta']==='cancelado'?'selected':''; ?>>cancelado</option></select><button class="btn btn-primary btn-sm">Guardar</button></form></td></tr><?php } ?></table></div></div>
</body></html>
