<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";

function subirImagenEditar($campo) {
    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] !== UPLOAD_ERR_OK) { return null; }
    $permitidas = ['jpg','jpeg','png','webp','gif'];
    $ext = strtolower(pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $permitidas)) { return null; }
    $nombre = 'producto_' . time() . '_' . random_int(1000,9999) . '.' . $ext;
    $destino = __DIR__ . '/../uploads/productos/' . $nombre;
    if (move_uploaded_file($_FILES[$campo]['tmp_name'], $destino)) { return 'uploads/productos/' . $nombre; }
    return null;
}

$id = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conexion, "SELECT * FROM producto WHERE id_producto=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$producto = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$producto) { header("Location: productos.php"); exit(); }

if (isset($_POST['actualizar'])) {
    $categoria = (int)$_POST['categoria'];
    $nombre = limpiar($_POST['nombre']);
    $marca = limpiar($_POST['marca']);
    $descripcion = limpiar($_POST['descripcion']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $estado = limpiar($_POST['estado']);
    $imagenNueva = subirImagenEditar('imagen');
    $imagen = $imagenNueva ?: $producto['imagen'];

    $stmt = mysqli_prepare($conexion, "UPDATE producto SET id_categoria=?, nombre=?, marca=?, descripcion=?, precio=?, stock=?, imagen=?, estado=? WHERE id_producto=?");
    mysqli_stmt_bind_param($stmt, "isssdissi", $categoria, $nombre, $marca, $descripcion, $precio, $stock, $imagen, $estado, $id);
    mysqli_stmt_execute($stmt);
    header("Location: productos.php"); exit();
}
$categorias = mysqli_query($conexion, "SELECT * FROM categoria ORDER BY nombre_categoria");
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Editar producto</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body class="admin-body">
<div class="container my-5"><div class="card"><div class="card-body"><h4>Editar producto</h4><form method="POST" enctype="multipart/form-data" class="row g-3"><div class="col-md-3"><img class="product-img-lg" src="<?php echo e(imagenProducto($producto['imagen'], '../')); ?>"></div><div class="col-md-9"><div class="row g-3"><div class="col-md-4"><label>Categoria</label><select name="categoria" class="form-select" required><?php while ($c=mysqli_fetch_assoc($categorias)) { ?><option value="<?php echo $c['id_categoria']; ?>" <?php echo $c['id_categoria']==$producto['id_categoria']?'selected':''; ?>><?php echo e($c['nombre_categoria']); ?></option><?php } ?></select></div><div class="col-md-4"><label>Nombre</label><input type="text" name="nombre" class="form-control" value="<?php echo e($producto['nombre']); ?>" required></div><div class="col-md-4"><label>Marca</label><input type="text" name="marca" class="form-control" value="<?php echo e($producto['marca']); ?>"></div><div class="col-md-12"><label>Descripcion</label><textarea name="descripcion" class="form-control"><?php echo e($producto['descripcion']); ?></textarea></div><div class="col-md-3"><label>Precio</label><input type="number" step="0.01" min="0" name="precio" class="form-control" value="<?php echo e($producto['precio']); ?>" required></div><div class="col-md-3"><label>Stock</label><input type="number" min="0" name="stock" class="form-control" value="<?php echo e($producto['stock']); ?>" required></div><div class="col-md-3"><label>Nueva imagen</label><input type="file" name="imagen" class="form-control" accept="image/*"></div><div class="col-md-3"><label>Estado</label><select name="estado" class="form-select"><option value="activo" <?php echo $producto['estado']==='activo'?'selected':''; ?>>activo</option><option value="inactivo" <?php echo $producto['estado']==='inactivo'?'selected':''; ?>>inactivo</option></select></div></div></div><div class="col-12"><button name="actualizar" class="btn btn-warning">Guardar cambios</button> <a href="productos.php" class="btn btn-secondary">Cancelar</a></div></form></div></div></div>
</body></html>
