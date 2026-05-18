<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";
$usuarios = mysqli_query($conexion, "SELECT id_usuario,nombre,correo,rol,estado_2fa,fecha_registro FROM usuario ORDER BY id_usuario DESC");
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Usuarios</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body class="admin-body">
<nav class="navbar navbar-dark admin-navbar"><div class="container"><a class="navbar-brand">Usuarios registrados</a><a href="dashboard.php" class="btn btn-outline-light">Volver</a></div></nav>
<div class="container my-4"><h2 class="fw-bold">Usuarios</h2><div class="table-responsive"><table class="table table-hover bg-white"><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>2FA</th><th>Fecha</th></tr><?php while($u=mysqli_fetch_assoc($usuarios)){ ?><tr><td><?php echo $u['id_usuario']; ?></td><td><?php echo e($u['nombre']); ?></td><td><?php echo e($u['correo']); ?></td><td><span class="badge bg-<?php echo $u['rol']==='admin'?'dark':'primary'; ?>"><?php echo e($u['rol']); ?></span></td><td><?php echo $u['estado_2fa'] ? 'Verificado' : 'Pendiente'; ?></td><td><?php echo e($u['fecha_registro']); ?></td></tr><?php } ?></table></div></div>
</body></html>
