<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";
$id = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conexion, "SELECT * FROM categoria WHERE id_categoria=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$categoria = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$categoria) { header("Location: categorias.php"); exit(); }
if (isset($_POST['actualizar'])) {
    $nombre = limpiar($_POST['nombre']); $descripcion = limpiar($_POST['descripcion']);
    $stmt = mysqli_prepare($conexion, "UPDATE categoria SET nombre_categoria=?, descripcion=? WHERE id_categoria=?");
    mysqli_stmt_bind_param($stmt, "ssi", $nombre, $descripcion, $id); mysqli_stmt_execute($stmt);
    header("Location: categorias.php"); exit();
}
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Editar categoria</title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body class="admin-body"><nav class="navbar navbar-dark admin-navbar sticky-top"><div class="container"><a class="navbar-brand brand-logo" href="dashboard.php">PapuStore Admin</a><a href="categorias.php" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Volver</a></div></nav>
<div class="container my-5"><section class="page-hero"><h1 class="fw-bold mb-1">Editar categoria</h1><p class="mb-0 text-white-50">Modifica la informacion de la categoria seleccionada.</p></section><div class="card"><div class="card-body p-4"><form method="POST"><div class="mb-3"><label>Nombre</label><input type="text" name="nombre" class="form-control" value="<?php echo e($categoria['nombre_categoria']); ?>" required></div><div class="mb-3"><label>Descripcion</label><textarea name="descripcion" class="form-control" rows="4"><?php echo e($categoria['descripcion']); ?></textarea></div><button name="actualizar" class="btn btn-warning"><i class="bi bi-save me-2"></i> Guardar cambios</button> <a href="categorias.php" class="btn btn-outline-primary">Cancelar</a></form></div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script></body></html>