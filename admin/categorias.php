<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";

$mensaje = "";

if (isset($_POST['agregar'])) {
    $nombre = limpiar($_POST['nombre']);
    $descripcion = limpiar($_POST['descripcion']);

    $stmt = mysqli_prepare(
        $conexion,
        "INSERT INTO categoria(nombre_categoria, descripcion) VALUES(?,?)"
    );

    mysqli_stmt_bind_param($stmt, "ss", $nombre, $descripcion);
    mysqli_stmt_execute($stmt);

    $mensaje = "Categoria agregada correctamente";
}

if (isset($_GET['eliminar'])) {
    $id = (int)$_GET['eliminar'];

    $stmt = mysqli_prepare(
        $conexion,
        "SELECT COUNT(*) total FROM producto WHERE id_categoria=?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $total = mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmt)
    )['total'];

    if ($total > 0) {
        $mensaje = "No se puede eliminar porque tiene productos registrados";
    } else {
        $stmt = mysqli_prepare(
            $conexion,
            "DELETE FROM categoria WHERE id_categoria=?"
        );

        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        $mensaje = "Categoria eliminada correctamente";
    }
}

$buscar = limpiar($_GET['buscar'] ?? '');

$sql = "SELECT * FROM categoria";

if ($buscar != '') {
    $sql .= "
    WHERE (
        id_categoria LIKE ?
        OR nombre_categoria LIKE ?
    )
    ";
}

$sql .= " ORDER BY id_categoria DESC";

$stmt = mysqli_prepare($conexion, $sql);

if ($buscar != '') {
    $filtro = "%$buscar%";

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $filtro,
        $filtro
    );

    mysqli_stmt_execute($stmt);
    $categorias = mysqli_stmt_get_result($stmt);

} else {
    $categorias = mysqli_query($conexion, $sql);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Categorias</title>

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

    <a class="admin-link" href="productos.php">
        <i class="bi bi-phone"></i> Productos
    </a>

    <a class="admin-link active" href="categorias.php">
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
    <h1 class="fw-bold mb-1">Gestion de categorias</h1>
    <p class="mb-0 text-white-50">
        Organiza el catalogo para filtros, busqueda y administracion.
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
    <i class="bi bi-tags me-2"></i>Nueva categoria
</h4>

<form method="POST" class="row g-3">

<div class="col-md-4">
<label>Nombre</label>
<input type="text" name="nombre" class="form-control" required>
</div>

<div class="col-md-6">
<label>Descripcion</label>
<input type="text" name="descripcion" class="form-control">
</div>

<div class="col-md-2 d-flex align-items-end">
<button name="agregar" class="btn btn-primary w-100">
Agregar
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
    placeholder="Buscar por ID o nombre..."
    value="<?php echo e($buscar); ?>"
>

<button class="btn btn-primary">
    <i class="bi bi-search"></i> Buscar
</button>

<?php if($buscar != ''){ ?>
<a href="categorias.php" class="btn btn-secondary">
    Limpiar
</a>
<?php } ?>

</div>
</form>

</div>
</div>

<div class="card">
<div class="card-body">

<h4 class="mb-3">Listado de categorias</h4>

<div class="table-responsive">
<table class="table table-hover align-middle">

<tr>
<th>ID</th>
<th>Nombre</th>
<th>Descripcion</th>
<th>Acciones</th>
</tr>

<?php $hay = false; ?>
<?php while ($cat = mysqli_fetch_assoc($categorias)) { $hay = true; ?>

<tr>
<td>#<?php echo $cat['id_categoria']; ?></td>

<td class="fw-bold">
    <?php echo e($cat['nombre_categoria']); ?>
</td>

<td>
    <?php echo e($cat['descripcion']); ?>
</td>

<td>
<div class="admin-actions">

<a href="categoria_editar.php?id=<?php echo $cat['id_categoria']; ?>"
class="btn btn-warning btn-sm">
<i class="bi bi-pencil"></i> Editar
</a>

<a href="?eliminar=<?php echo $cat['id_categoria']; ?>"
onclick="return confirm('Eliminar categoria?')"
class="btn btn-danger btn-sm">
<i class="bi bi-trash"></i>
</a>

</div>
</td>
</tr>

<?php } ?>

<?php if(!$hay){ ?>
<tr>
<td colspan="4" class="text-center text-muted py-4">
No se encontraron categorias.
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