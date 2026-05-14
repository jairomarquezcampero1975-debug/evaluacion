<?php
include("../includes/auth.php");
protegerRuta();
soloAdmin();
include("../config/conexion.php");

$total_productos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM producto"))['total'];
$total_usuarios = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM usuario"))['total'];
$total_ventas = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM venta"))['total'];
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
        <a href="../logout.php" class="btn btn-danger">Cerrar sesion</a>
    </div>
</nav>

<div class="container mt-5">
    <h2>Bienvenido administrador: <?php echo $_SESSION['nombre']; ?></h2>
    <p>Este panel sera ampliado por el Estudiante 2.</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body text-center">
                    <h4>Productos</h4>
                    <h2><?php echo $total_productos; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body text-center">
                    <h4>Usuarios</h4>
                    <h2><?php echo $total_usuarios; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body text-center">
                    <h4>Ventas</h4>
                    <h2><?php echo $total_ventas; ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>