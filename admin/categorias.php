<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";
$mensaje = "";

if (isset($_POST['agregar'])) {
    $nombre = limpiar($_POST['nombre']);
    $descripcion = limpiar($_POST['descripcion']);
    $stmt = mysqli_prepare($conexion, "INSERT INTO categoria(nombre_categoria, descripcion) VALUES(?,?)");
    mysqli_stmt_bind_param($stmt, "ss", $nombre, $descripcion);
    mysqli_stmt_execute($stmt);
    $mensaje = "Categoria agregada correctamente";
}

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];
    $stmt = mysqli_prepare($conexion, "SELECT COUNT(*) total FROM producto WHERE id_categoria=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $total = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
    if ($total > 0) { $mensaje = "No se puede eliminar porque tiene productos registrados"; }
    else { $stmt = mysqli_prepare($conexion, "DELETE FROM categoria WHERE id_categoria=?"); mysqli_stmt_bind_param($stmt, "i", $id); mysqli_stmt_execute($stmt); $mensaje = "Categoria eliminada correctamente"; }
}
$categorias = mysqli_query($conexion, "SELECT * FROM categoria ORDER BY id_categoria DESC");
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Categorias</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body class="admin-body">
<nav class="navbar navbar-dark admin-navbar"><div class="container"><a class="navbar-brand">Gestion de categorias</a><a href="dashboard.php" class="btn btn-outline-light">Volver</a></div></nav>
<div class="container my-4"><h2 class="fw-bold">Categorias</h2><?php if ($mensaje) { ?><div class="alert alert-info"><?php echo e($mensaje); ?></div><?php } ?>
<div class="card mb-4"><div class="card-body"><h5>Agregar categoria</h5><form method="POST" class="row g-3"><div class="col-md-4"><input type="text" name="nombre" class="form-control" placeholder="Nombre" required></div><div class="col-md-6"><input type="text" name="descripcion" class="form-control" placeholder="Descripcion"></div><div class="col-md-2"><button name="agregar" class="btn btn-primary w-100">Agregar</button></div></form></div></div>
<div class="table-responsive"><table class="table table-hover bg-white"><tr><th>ID</th><th>Nombre</th><th>Descripcion</th><th>Acciones</th></tr><?php while ($cat = mysqli_fetch_assoc($categorias)) { ?><tr><td><?php echo $cat['id_categoria']; ?></td><td><?php echo e($cat['nombre_categoria']); ?></td><td><?php echo e($cat['descripcion']); ?></td><td><a href="categoria_editar.php?id=<?php echo $cat['id_categoria']; ?>" class="btn btn-warning btn-sm">Editar</a> <a href="?eliminar=<?php echo $cat['id_categoria']; ?>" onclick="return confirm('Eliminar categoria?')" class="btn btn-danger btn-sm">Eliminar</a></td></tr><?php } ?></table></div></div>
</body></html>
