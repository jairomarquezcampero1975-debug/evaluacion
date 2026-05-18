<?php
require_once "../includes/auth.php";
protegerRuta();
soloCliente();
require_once "../config/conexion.php";
$id_usuario = $_SESSION['id_usuario'];
$buscar = limpiar($_GET['buscar'] ?? '');
$categoria = (int)($_GET['categoria'] ?? 0);
$marca = limpiar($_GET['marca'] ?? '');

$sql = "SELECT p.*, c.nombre_categoria FROM producto p INNER JOIN categoria c ON p.id_categoria=c.id_categoria WHERE p.estado='activo'";
$params = [];
$types = "";
if ($buscar !== '') { $like = "%$buscar%"; $sql .= " AND (p.nombre LIKE ? OR p.marca LIKE ? OR p.descripcion LIKE ?)"; $params[]=$like; $params[]=$like; $params[]=$like; $types.="sss"; }
if ($categoria > 0) { $sql .= " AND p.id_categoria=?"; $params[]=$categoria; $types.="i"; }
if ($marca !== '') { $sql .= " AND p.marca=?"; $params[]=$marca; $types.="s"; }
$sql .= " ORDER BY p.id_producto DESC";
$stmt = mysqli_prepare($conexion, $sql);
if ($params) { mysqli_stmt_bind_param($stmt, $types, ...$params); }
mysqli_stmt_execute($stmt);
$productos = mysqli_stmt_get_result($stmt);
$categorias = mysqli_query($conexion, "SELECT * FROM categoria ORDER BY nombre_categoria");
$marcas = mysqli_query($conexion, "SELECT DISTINCT marca FROM producto WHERE marca IS NOT NULL AND marca<>'' ORDER BY marca");
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Panel Cliente</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark nav-glass"><div class="container"><a class="navbar-brand brand-logo fw-bold">TecnoStore</a><button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse" id="menu"><div class="ms-auto d-flex flex-wrap gap-2"><a href="favoritos.php" class="btn btn-outline-light btn-sm">Favoritos</a><a href="historial.php" class="btn btn-outline-info btn-sm">Historial</a><a href="../logout.php" class="btn btn-danger btn-sm">Cerrar sesion</a></div></div></div></nav>
<div class="container my-5"><h2 class="fw-bold">Bienvenido, <?php echo e($_SESSION['nombre']); ?></h2><p class="text-muted">Busca productos, agrega favoritos y compra desde el carrito.</p>
<form method="GET" class="card mb-5"><div class="card-body"><div class="row g-3"><div class="col-md-4"><input type="text" name="buscar" value="<?php echo e($buscar); ?>" class="form-control" placeholder="Buscar producto, marca o descripcion"></div><div class="col-md-3"><select name="categoria" class="form-select"><option value="0">Todas las categorias</option><?php while($cat=mysqli_fetch_assoc($categorias)){ ?><option value="<?php echo $cat['id_categoria']; ?>" <?php echo $categoria==$cat['id_categoria']?'selected':''; ?>><?php echo e($cat['nombre_categoria']); ?></option><?php } ?></select></div><div class="col-md-3"><select name="marca" class="form-select"><option value="">Todas las marcas</option><?php while($m=mysqli_fetch_assoc($marcas)){ ?><option value="<?php echo e($m['marca']); ?>" <?php echo $marca===$m['marca']?'selected':''; ?>><?php echo e($m['marca']); ?></option><?php } ?></select></div><div class="col-md-2"><button class="btn btn-primary w-100">Filtrar</button></div></div></div></form>
<div class="row g-4"><?php while($producto=mysqli_fetch_assoc($productos)){ ?><div class="col-md-4"><div class="card h-100 producto-card"><img class="product-img" src="<?php echo e(imagenProducto($producto['imagen'], '../')); ?>"><div class="card-body d-flex flex-column"><span class="badge bg-primary align-self-start mb-3"><?php echo e($producto['nombre_categoria']); ?></span><h5><?php echo e($producto['nombre']); ?></h5><p class="flex-grow-1"><?php echo e($producto['descripcion']); ?></p><p><strong>Marca:</strong> <?php echo e($producto['marca']); ?></p><p><span class="badge bg-success">Stock: <?php echo $producto['stock']; ?></span></p><h3 class="text-primary">Bs <?php echo number_format($producto['precio'],2); ?></h3><div class="d-grid gap-2 mt-auto"><a href="ver_producto.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-primary">Ver producto</a><a href="agregar_favorito.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-outline-danger">Favorito</a><a href="agregar_carrito.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-dark">Agregar carrito</a></div></div></div></div><?php } ?></div></div>
<button class="btn btn-primary rounded-circle position-fixed shadow cart-floating" data-bs-toggle="modal" data-bs-target="#carritoModal">Carrito</button>
<?php include "modal_carrito.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script></body></html>
