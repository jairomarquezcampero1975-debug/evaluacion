<?php
include("../includes/auth.php");
protegerRuta();
soloAdmin();
include("../config/conexion.php");

$total_productos = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) AS total FROM producto")
)['total'];

$total_usuarios = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) AS total FROM usuario")
)['total'];

$total_ventas = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) AS total FROM venta")
)['total'];

$total_categorias = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) AS total FROM categoria")
)['total'];

$stock_bajo = mysqli_num_rows(
    mysqli_query($conexion, "SELECT * FROM producto WHERE stock <= 5")
);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Administrador</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand">Panel Administrador</a>
        <a href="../logout.php" class="btn btn-danger">Cerrar sesión</a>
    </div>
</nav>

<div class="container mt-4">

    <h2>Bienvenido, <?php echo $_SESSION['nombre']; ?></h2>

    <div class="row mt-4 g-3">

        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h5>Productos</h5>
                    <h2><?php echo $total_productos; ?></h2>
                    <a href="productos.php" class="btn btn-light btn-sm">
                        Gestionar
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5>Categorías</h5>
                    <h2><?php echo $total_categorias; ?></h2>
                    <a href="categorias.php" class="btn btn-light btn-sm">
                        Gestionar
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning">
                <div class="card-body text-center">
                    <h5>Usuarios</h5>
                    <h2><?php echo $total_usuarios; ?></h2>
                    <a href="usuarios.php" class="btn btn-dark btn-sm">
                        Ver
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h5>Ventas</h5>
                    <h2><?php echo $total_ventas; ?></h2>
                    <a href="ventas.php" class="btn btn-light btn-sm">
                        Ver
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-5">
        <div class="alert alert-danger">
            Productos con stock bajo:
            <strong><?php echo $stock_bajo; ?></strong>
            <a href="stock.php" class="btn btn-sm btn-danger ms-3">
                Revisar stock
            </a>
        </div>
    </div>

</div>


</body>
</html>