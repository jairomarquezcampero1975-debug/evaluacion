<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";
$mensaje = "";

function subirImagenProducto($campo) {
    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] !== UPLOAD_ERR_OK) { return null; }
    $permitidas = ['jpg','jpeg','png','webp','gif'];
    $ext = strtolower(pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $permitidas)) { return null; }
    $nombre = 'producto_' . time() . '_' . random_int(1000,9999) . '.' . $ext;
    $destino = __DIR__ . '/../uploads/productos/' . $nombre;
    if (move_uploaded_file($_FILES[$campo]['tmp_name'], $destino)) { return 'uploads/productos/' . $nombre; }
    return null;
}

if (isset($_POST['agregar'])) {
    $categoria = (int)$_POST['categoria'];
    $nombre = limpiar($_POST['nombre']);
    $marca = limpiar($_POST['marca']);
    $descripcion = limpiar($_POST['descripcion']);
    $precio = (float)$_POST['precio'];
    $stock = (int)$_POST['stock'];
    $estado = limpiar($_POST['estado']);
    $imagen = subirImagenProducto('imagen');

    $stmt = mysqli_prepare($conexion, "INSERT INTO producto(id_categoria,nombre,marca,descripcion,precio,stock,imagen,estado) VALUES(?,?,?,?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "isssdiss", $categoria, $nombre, $marca, $descripcion, $precio, $stock, $imagen, $estado);
    mysqli_stmt_execute($stmt);
    $mensaje = "Producto agregado correctamente";
}

if (isset($_GET['eliminar']) || isset($_GET['activar'])) {
    $id = (int)($_GET['eliminar'] ?? $_GET['activar']);
    $estado = isset($_GET['activar']) ? 'activo' : 'inactivo';
    $stmt = mysqli_prepare($conexion, "UPDATE producto SET estado=? WHERE id_producto=?");
    mysqli_stmt_bind_param($stmt, "si", $estado, $id);
    mysqli_stmt_execute($stmt);
    $mensaje = $estado === 'activo' ? "Producto activado correctamente" : "Producto desactivado correctamente";
}

$categorias = mysqli_query($conexion, "SELECT * FROM categoria ORDER BY nombre_categoria");
$productos = mysqli_query($conexion, "SELECT p.*, c.nombre_categoria FROM producto p INNER JOIN categoria c ON p.id_categoria=c.id_categoria ORDER BY p.id_producto DESC");
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Productos</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body class="admin-body">
<nav class="navbar navbar-dark admin-navbar"><div class="container"><a class="navbar-brand">Gestion de productos</a><a href="dashboard.php" class="btn btn-outline-light">Volver</a></div></nav>
<div class="container my-4"><h2 class="fw-bold">Productos</h2><?php if ($mensaje) { ?><div class="alert alert-info"><?php echo e($mensaje); ?></div><?php } ?>
<div class="card mb-4"><div class="card-body"><h5>Agregar producto</h5><form method="POST" enctype="multipart/form-data" class="row g-3"><div class="col-md-3"><label>Categoria</label><select name="categoria" class="form-select" required><?php while ($c=mysqli_fetch_assoc($categorias)) { ?><option value="<?php echo $c['id_categoria']; ?>"><?php echo e($c['nombre_categoria']); ?></option><?php } ?></select></div><div class="col-md-3"><label>Nombre</label><input type="text" name="nombre" class="form-control" required></div><div class="col-md-2"><label>Marca</label><input type="text" name="marca" class="form-control"></div><div class="col-md-2"><label>Precio</label><input type="number" step="0.01" min="0" name="precio" class="form-control" required></div><div class="col-md-2"><label>Stock</label><input type="number" min="0" name="stock" class="form-control" required></div><div class="col-md-8"><label>Descripcion</label><textarea name="descripcion" class="form-control"></textarea></div><div class="col-md-2"><label>Imagen</label><input type="file" name="imagen" class="form-control" accept="image/*"></div><div class="col-md-2"><label>Estado</label><select name="estado" class="form-select"><option value="activo">activo</option><option value="inactivo">inactivo</option></select></div><div class="col-md-12"><button name="agregar" class="btn btn-primary">Agregar producto</button></div></form></div></div>
<div class="table-responsive"><table class="table table-hover bg-white"><tr><th>Imagen</th><th>ID</th><th>Nombre</th><th>Categoria</th><th>Marca</th><th>Precio</th><th>Stock</th><th>Estado</th><th>Acciones</th></tr><?php while ($p=mysqli_fetch_assoc($productos)) { ?><tr><td><img class="product-img-sm" src="<?php echo e(imagenProducto($p['imagen'], '../')); ?>"></td><td><?php echo $p['id_producto']; ?></td><td><?php echo e($p['nombre']); ?></td><td><?php echo e($p['nombre_categoria']); ?></td><td><?php echo e($p['marca']); ?></td><td>Bs <?php echo number_format($p['precio'],2); ?></td><td><?php echo $p['stock']; ?></td><td><span class="badge bg-<?php echo $p['estado']==='activo'?'success':'secondary'; ?>"><?php echo e($p['estado']); ?></span></td><td><a href="producto_editar.php?id=<?php echo $p['id_producto']; ?>" class="btn btn-warning btn-sm">Editar</a> <?php if ($p['estado']==='activo') { ?><a href="?eliminar=<?php echo $p['id_producto']; ?>" class="btn btn-danger btn-sm">Desactivar</a><?php } else { ?><a href="?activar=<?php echo $p['id_producto']; ?>" class="btn btn-success btn-sm">Activar</a><?php } ?></td></tr><?php } ?></table></div></div>
</body></html>
