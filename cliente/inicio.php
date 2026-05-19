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
$params = []; $types = "";
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
$total_productos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) total FROM producto WHERE estado='activo'"))['total'];
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Tienda</title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"><link rel="stylesheet" href="../assets/css/estilos.css"></head><body><nav class="navbar navbar-expand-lg navbar-dark nav-glass sticky-top"><div class="container"><a class="navbar-brand brand-logo" href="inicio.php">PapuStore</a><button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menuCliente"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse" id="menuCliente"><div class="ms-auto d-flex flex-wrap gap-2 mt-3 mt-lg-0"><a href="inicio.php" class="btn btn-outline-light btn-sm"><i class="bi bi-shop"></i> Tienda</a><a href="favoritos.php" class="btn btn-outline-light btn-sm"><i class="bi bi-heart"></i> Favoritos</a><a href="historial.php" class="btn btn-outline-light btn-sm"><i class="bi bi-clock-history"></i> Historial</a><a href="../logout.php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</a></div></div></div></nav>
<div class="container my-5">
    <section class="client-hero mb-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="mini-badge"><i class="bi bi-person-check"></i> Cliente verificado con 2FA</span>
                <h1 class="display-5 fw-bold mb-2">Hola, <?php echo e($_SESSION['nombre']); ?></h1>
                <p class="mb-0 text-white-50">Busca productos, filtra por categoria o marca, guarda favoritos y compra desde el carrito.</p>
            </div>
            <div class="col-lg-4 text-lg-end"><span class="hero-badge mb-0"><i class="bi bi-grid"></i> <?php echo $total_productos; ?> productos activos</span></div>
        </div>
    </section>
    <form method="GET" class="card filter-card mb-5"><div class="card-body"><div class="row g-3 align-items-end"><div class="col-md-4"><label>Buscar</label><input type="text" name="buscar" value="<?php echo e($buscar); ?>" class="form-control" placeholder="Producto, marca o descripcion"></div><div class="col-md-3"><label>Categoria</label><select name="categoria" class="form-select"><option value="0">Todas las categorias</option><?php while($cat=mysqli_fetch_assoc($categorias)){ ?><option value="<?php echo $cat['id_categoria']; ?>" <?php echo $categoria==$cat['id_categoria']?'selected':''; ?>><?php echo e($cat['nombre_categoria']); ?></option><?php } ?></select></div><div class="col-md-3"><label>Marca</label><select name="marca" class="form-select"><option value="">Todas las marcas</option><?php while($m=mysqli_fetch_assoc($marcas)){ ?><option value="<?php echo e($m['marca']); ?>" <?php echo $marca===$m['marca']?'selected':''; ?>><?php echo e($m['marca']); ?></option><?php } ?></select></div><div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-search"></i> Filtrar</button></div></div></div></form>
    <div class="row g-4"><?php $hay=false; while($producto=mysqli_fetch_assoc($productos)){ $hay=true; ?><div class="col-md-6 col-xl-4"><div class="product-card"><img class="product-img" src="<?php echo e(imagenProducto($producto['imagen'], '../')); ?>"><div class="card-body d-flex flex-column"><div class="d-flex justify-content-between align-items-start mb-3"><span class="badge badge-soft"><?php echo e($producto['nombre_categoria']); ?></span><span class="badge bg-<?php echo $producto['stock']<=5?'danger':'success'; ?>">Stock <?php echo $producto['stock']; ?></span></div><h5><?php echo e($producto['nombre']); ?></h5><p class="flex-grow-1"><?php echo e($producto['descripcion']); ?></p><p><i class="bi bi-award me-1"></i><strong>Marca:</strong> <?php echo e($producto['marca']); ?></p><div class="price mb-3">Bs <?php echo number_format($producto['precio'],2); ?></div><div class="d-grid gap-2 mt-auto"><a href="ver_producto.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-primary"><i class="bi bi-eye me-2"></i> Ver producto</a><div class="d-flex gap-2"><a href="agregar_favorito.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-outline-danger w-50"><i class="bi bi-heart"></i></a><a href="agregar_carrito.php?id=<?php echo $producto['id_producto']; ?>" class="btn btn-dark w-50"><i class="bi bi-cart-plus"></i></a></div></div></div></div></div><?php } ?><?php if(!$hay){ ?><div class="col-12"><div class="empty-state"><h4>No se encontraron productos</h4><p class="text-muted mb-0">Prueba con otra busqueda o quita los filtros.</p></div></div><?php } ?></div>
</div>
<button class="btn btn-primary position-fixed cart-floating" data-bs-toggle="modal" data-bs-target="#carritoModal"><i class="bi bi-cart3 d-block"></i> Carrito</button>
<?php include "modal_carrito.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script><script src="../assets/js/script.js"></script></body></html>