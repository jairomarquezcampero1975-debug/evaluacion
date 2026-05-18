<?php
require_once "../includes/auth.php";
protegerRuta();
soloCliente();
require_once "../config/conexion.php";

$id_usuario = $_SESSION['id_usuario'];

$stmt = mysqli_prepare($conexion, "
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
ORDER BY v.id_venta DESC
");

mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$historial = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Historial</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/estilos.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark nav-glass">
    <div class="container">
        <a class="navbar-brand brand-logo">Historial de compras</a>

        <div>
            <a href="inicio.php" class="btn btn-outline-light">Volver</a>
            <a href="../logout.php" class="btn btn-danger">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container my-5">

    <h2 class="fw-bold mb-4">Mis compras realizadas</h2>

    <?php if(isset($_GET['compra'])){ ?>
        <div class="alert alert-success shadow-sm">
            Compra registrada correctamente.
        </div>
    <?php } ?>

    <div class="table-responsive shadow-sm">
        <table class="table table-hover table-bordered bg-white align-middle">

            <thead class="table-dark">
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
            </thead>

            <tbody>
                <?php while($row = mysqli_fetch_assoc($historial)){ ?>
                <tr>
                    <td><?php echo $row['id_venta']; ?></td>
                    <td><?php echo $row['fecha']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['marca']; ?></td>
                    <td><?php echo $row['cantidad']; ?></td>
                    <td>Bs <?php echo number_format($row['precio_unitario'], 2); ?></td>
                    <td>Bs <?php echo number_format($row['subtotal'], 2); ?></td>
                    <td>Bs <?php echo number_format($row['total'], 2); ?></td>
                    <td>
                        <span class="badge bg-success">
                            <?php echo $row['estado_venta']; ?>
                        </span>
                    </td>
                </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>