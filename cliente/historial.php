<?php
require_once "../includes/auth.php";
protegerRuta();
soloCliente();
require_once "../config/conexion.php";

$id_usuario = $_SESSION['id_usuario'];
$buscar = limpiar($_GET['buscar'] ?? '');

$sql = "
SELECT 
    v.id_venta,
    v.fecha,
    v.total,
    v.estado_venta,
    p.nombre,
    p.marca,
    d.cantidad,
    (d.subtotal / d.cantidad) AS precio_unitario,
    d.subtotal
FROM venta v
INNER JOIN detalle_venta d ON v.id_venta = d.id_venta
INNER JOIN producto p ON d.id_producto = p.id_producto
WHERE v.id_usuario = ?
";

if ($buscar !== '') {
    $sql .= "
    AND (
    v.id_venta LIKE ?
    OR p.nombre LIKE ?
    OR p.marca LIKE ?
    OR v.fecha LIKE ?
    )
    ";
}

$sql .= " ORDER BY v.id_venta DESC";

$stmt = mysqli_prepare($conexion, $sql);

if ($buscar !== '') {
    $filtro = "%$buscar%";
    mysqli_stmt_bind_param(
        $stmt,
        "issss",
        $id_usuario,
        $filtro,
        $filtro,
        $filtro,
        $filtro
    );
} else {
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
}

mysqli_stmt_execute($stmt);
$historial = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Historial</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/estilos.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark nav-glass sticky-top">
    <div class="container">

        <a class="navbar-brand brand-logo" href="inicio.php">
            PapuStore
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menuCliente">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuCliente">
            <div class="ms-auto d-flex flex-wrap gap-2 mt-3 mt-lg-0">

                <a href="inicio.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-shop"></i> Tienda
                </a>

                <a href="favoritos.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-heart"></i> Favoritos
                </a>

                <a href="historial.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-clock-history"></i> Historial
                </a>

                <a href="../logout.php" class="btn btn-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>

            </div>
        </div>
    </div>
</nav>

<div class="container my-5">

    <section class="client-hero mb-5">
        <h1 class="fw-bold mb-2">
            Historial de compras
        </h1>

        <p class="mb-0 text-white-50">
            Consulta todas tus compras registradas y sus estados.
        </p>
    </section>

    <?php if(isset($_GET['compra'])){ ?>
        <div class="alert alert-success shadow-sm">
            Compra registrada correctamente.
        </div>
    <?php } ?>

    <div class="card mb-4">
        <div class="card-body">

            <form method="GET">
                <div class="input-group">

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Buscar por ID, producto o fecha..."
                        value="<?php echo e($buscar); ?>"
                    >

                    <button class="btn btn-primary">
                        <i class="bi bi-search"></i> Buscar
                    </button>

                    <?php if($buscar != ''){ ?>
                        <a href="historial.php" class="btn btn-secondary">
                            Limpiar
                        </a>
                    <?php } ?>

                </div>
            </form>

        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <h4 class="mb-3">
                Mis compras realizadas
            </h4>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <tr>
                        <th>ID Venta</th>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Marca</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                        <th>Total Venta</th>
                        <th>Estado</th>
                    </tr>

                    <?php $hay = false; ?>

                    <?php while($row = mysqli_fetch_assoc($historial)){ $hay = true; ?>

                    <tr>
                        <td class="fw-bold">
                            #<?php echo (int)$row['id_venta']; ?>
                        </td>

                        <td>
                            <?php echo e($row['fecha']); ?>
                        </td>

                        <td>
                            <?php echo e($row['nombre']); ?>
                        </td>

                        <td>
                            <?php echo e($row['marca']); ?>
                        </td>

                        <td>
                            <?php echo (int)$row['cantidad']; ?>
                        </td>

                        <td>
                            Bs <?php echo number_format($row['precio_unitario'], 2); ?>
                        </td>

                        <td>
                            Bs <?php echo number_format($row['subtotal'], 2); ?>
                        </td>

                        <td class="fw-bold">
                            Bs <?php echo number_format($row['total'], 2); ?>
                        </td>

                        <td>
                            <span class="badge bg-<?php echo $row['estado_venta']==='pagado' ? 'success' : ($row['estado_venta']==='cancelado' ? 'danger' : 'warning'); ?>">
                                <?php echo e($row['estado_venta']); ?>
                            </span>
                        </td>
                    </tr>

                    <?php } ?>

                    <?php if (!$hay) { ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            No se encontraron compras.
                        </td>
                    </tr>
                    <?php } ?>

                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>

</body>
</html>