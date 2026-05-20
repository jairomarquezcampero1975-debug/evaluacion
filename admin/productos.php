<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";

$mensaje = "";

function subirImagenProducto($campo) {
    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $permitidas = ['jpg','jpeg','png','webp','gif','svg'];
    $ext = strtolower(pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $permitidas)) {
        return null;
    }

    $nombre = 'producto_' . time() . '_' . random_int(1000,9999) . '.' . $ext;

    if (!is_dir(__DIR__ . '/../uploads/productos')) {
        mkdir(__DIR__ . '/../uploads/productos', 0777, true);
    }

    $destino = __DIR__ . '/../uploads/productos/' . $nombre;

    if (move_uploaded_file($_FILES[$campo]['tmp_name'], $destino)) {
        return 'uploads/productos/' . $nombre;
    }

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

    $stmt = mysqli_prepare(
        $conexion,
        "INSERT INTO producto
        (id_categoria,nombre,marca,descripcion,precio,stock,imagen,estado)
        VALUES(?,?,?,?,?,?,?,?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "isssdiss",
        $categoria,
        $nombre,
        $marca,
        $descripcion,
        $precio,
        $stock,
        $imagen,
        $estado
    );

    mysqli_stmt_execute($stmt);

    $mensaje = "Producto agregado correctamente";
}

if (isset($_GET['eliminar']) || isset($_GET['activar'])) {
    $id = (int)($_GET['eliminar'] ?? $_GET['activar']);
    $estado = isset($_GET['activar']) ? 'activo' : 'inactivo';

    $stmt = mysqli_prepare(
        $conexion,
        "UPDATE producto SET estado=? WHERE id_producto=?"
    );

    mysqli_stmt_bind_param($stmt, "si", $estado, $id);
    mysqli_stmt_execute($stmt);

    $mensaje = $estado === 'activo'
        ? "Producto activado correctamente"
        : "Producto desactivado correctamente";
}

$buscar = limpiar($_GET['buscar'] ?? '');

$categorias = mysqli_query(
    $conexion,
    "SELECT * FROM categoria ORDER BY nombre_categoria"
);

$sql = "
SELECT p.*, c.nombre_categoria
FROM producto p
INNER JOIN categoria c ON p.id_categoria = c.id_categoria
";

if ($buscar != '') {
    $sql .= "
    WHERE (
        p.id_producto LIKE ?
        OR p.nombre LIKE ?
        OR c.nombre_categoria LIKE ?
        OR p.marca LIKE ?
        OR p.precio LIKE ?
        OR p.stock LIKE ?
        OR p.estado LIKE ?
    )
    ";
}

$sql .= " ORDER BY p.id_producto DESC";

$stmt = mysqli_prepare($conexion, $sql);

if ($buscar != '') {
    $filtro = "%$buscar%";

    mysqli_stmt_bind_param(
        $stmt,
        "sssssss",
        $filtro,
        $filtro,
        $filtro,
        $filtro,
        $filtro,
        $filtro,
        $filtro
    );

    mysqli_stmt_execute($stmt);
    $productos = mysqli_stmt_get_result($stmt);

} else {
    $productos = mysqli_query($conexion, $sql);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Productos</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/estilos.css">
</head>

<body class="admin-body">

<nav class="navbar navbar-dark admin-navbar sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand brand-logo" href="dashboard.php">
            PapuStore Admin
        </a>

        <div class="d-flex gap-2">
            <a href="../logout.php" class="btn btn-danger btn-sm">
                <i class="bi bi-box-arrow-right"></i> Salir
            </a>
        </div>
    </div>
</nav>

<div class="admin-shell">

<aside class="admin-sidebar">
    <div class="menu-title">Panel</div>

    <a class="admin-link" href="dashboard.php">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a class="admin-link active" href="productos.php">
        <i class="bi bi-phone"></i> Productos
    </a>

    <a class="admin-link" href="categorias.php">
        <i class="bi bi-tags"></i> Categorias
    </a>

    <a class="admin-link" href="stock.php">
        <i class="bi bi-box-seam"></i> Stock
    </a>

    <a class="admin-link" href="ventas.php">
        <i class="bi bi-receipt"></i> Ventas
    </a>

    <a class="admin-link" href="usuarios.php">
        <i class="bi bi-people"></i> Usuarios
    </a>
</aside>

<main class="admin-content">

<section class="page-hero">
    <h1 class="fw-bold mb-1">Gestion de productos</h1>
    <p class="mb-0 text-white-50">
        Crea, edita, activa, desactiva y controla el catalogo principal.
    </p>
</section>

<?php if ($mensaje) { ?>
<div class="alert alert-info shadow-sm">
    <?php echo e($mensaje); ?>
</div>
<?php } ?>

<div class="card mb-4">
<div class="card-body">

<h4 class="mb-3">
    <i class="bi bi-plus-circle me-2"></i>Agregar producto
</h4>

<form method="POST" enctype="multipart/form-data" class="row g-3">

<div class="col-md-3">
<label>Categoria</label>
<select name="categoria" class="form-select" required>
<?php while ($c=mysqli_fetch_assoc($categorias)) { ?>
<option value="<?php echo $c['id_categoria']; ?>">
    <?php echo e($c['nombre_categoria']); ?>
</option>
<?php } ?>
</select>
</div>

<div class="col-md-3">
<label>Nombre</label>
<input type="text" name="nombre" class="form-control" required>
</div>

<div class="col-md-2">
<label>Marca</label>
<input type="text" name="marca" class="form-control">
</div>

<div class="col-md-2">
<label>Precio</label>
<input type="number" step="0.01" min="0" name="precio" class="form-control" required>
</div>

<div class="col-md-2">
<label>Stock</label>
<input type="number" min="0" name="stock" class="form-control" required>
</div>

<div class="col-md-8">
<label>Descripcion</label>
<textarea name="descripcion" class="form-control" rows="3"></textarea>
</div>

<div class="col-md-2">
<label>Imagen</label>
<input type="file" name="imagen" class="form-control" accept="image/*">
</div>

<div class="col-md-2">
<label>Estado</label>
<select name="estado" class="form-select">
<option value="activo">activo</option>
<option value="inactivo">inactivo</option>
</select>
</div>

<div class="col-md-12">
<button name="agregar" class="btn btn-primary">
<i class="bi bi-save me-2"></i> Guardar producto
</button>
</div>

</form>
</div>
</div>

<div class="card mb-4">
<div class="card-body">

<form method="GET">
<div class="input-group">

<input
    type="text"
    name="buscar"
    class="form-control"
    placeholder="Buscar por ID, nombre, categoria, marca, precio, stock o estado..."
    value="<?php echo e($buscar); ?>"
>

<button class="btn btn-primary">
    <i class="bi bi-search"></i> Buscar
</button>

<?php if($buscar != ''){ ?>
<a href="productos.php" class="btn btn-secondary">
    Limpiar
</a>
<?php } ?>

</div>
</form>

</div>
</div>

<div class="card">
<div class="card-body">

<h4 class="mb-3">Listado de productos</h4>

<div class="table-responsive">
<table class="table table-hover align-middle">

<tr>
<th>Imagen</th>
<th>ID</th>
<th>Nombre</th>
<th>Categoria</th>
<th>Marca</th>
<th>Precio</th>
<th>Stock</th>
<th>Estado</th>
<th>Acciones</th>
</tr>

<?php $hay = false; ?>
<?php while ($p=mysqli_fetch_assoc($productos)) { $hay = true; ?>

<tr>
<td>
<img class="product-img-sm"
src="<?php echo e(imagenProducto($p['imagen'], '../')); ?>">
</td>

<td>#<?php echo $p['id_producto']; ?></td>

<td class="fw-bold">
<?php echo e($p['nombre']); ?>
</td>

<td>
<?php echo e($p['nombre_categoria']); ?>
</td>

<td>
<?php echo e($p['marca']); ?>
</td>

<td class="fw-bold">
Bs <?php echo number_format($p['precio'],2); ?>
</td>

<td>
<span class="badge bg-<?php echo $p['stock']<=5?'danger':'success'; ?>">
<?php echo $p['stock']; ?>
</span>
</td>

<td>
<span class="badge bg-<?php echo $p['estado']==='activo'?'success':'secondary'; ?>">
<?php echo e($p['estado']); ?>
</span>
</td>

<td>
<div class="admin-actions">

<a href="producto_editar.php?id=<?php echo $p['id_producto']; ?>"
class="btn btn-warning btn-sm">
<i class="bi bi-pencil"></i>
</a>

<?php if ($p['estado']==='activo') { ?>

<a href="?eliminar=<?php echo $p['id_producto']; ?>"
class="btn btn-danger btn-sm">
Desactivar
</a>

<?php } else { ?>

<a href="?activar=<?php echo $p['id_producto']; ?>"
class="btn btn-success btn-sm">
Activar
</a>

<?php } ?>

</div>
</td>
</tr>

<?php } ?>

<?php if(!$hay){ ?>
<tr>
<td colspan="9" class="text-center text-muted py-4">
No se encontraron productos.
</td>
</tr>
<?php } ?>

</table>
</div>
</div>
</div>

</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>