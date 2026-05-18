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
    $nombre = limpiar($_POST['nombre']);
    $descripcion = limpiar($_POST['descripcion']);
    $stmt = mysqli_prepare($conexion, "UPDATE categoria SET nombre_categoria=?, descripcion=? WHERE id_categoria=?");
    mysqli_stmt_bind_param($stmt, "ssi", $nombre, $descripcion, $id);
    mysqli_stmt_execute($stmt);
    header("Location: categorias.php"); exit();
}
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Editar categoria</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body class="admin-body">
<div class="container my-5"><div class="card"><div class="card-body"><h4>Editar categoria</h4><form method="POST"><div class="mb-3"><label>Nombre</label><input type="text" name="nombre" class="form-control" value="<?php echo e($categoria['nombre_categoria']); ?>" required></div><div class="mb-3"><label>Descripcion</label><textarea name="descripcion" class="form-control"><?php echo e($categoria['descripcion']); ?></textarea></div><button name="actualizar" class="btn btn-warning">Guardar cambios</button> <a href="categorias.php" class="btn btn-secondary">Cancelar</a></form></div></div></div>
</body></html>
