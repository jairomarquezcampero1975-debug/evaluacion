<?php
include("../includes/auth.php");
protegerRuta();
soloCliente();
include("../config/conexion.php");

$productos = mysqli_query($conexion, "SELECT p.*, c.nombre_categoria 
FROM producto p 
INNER JOIN categoria c ON p.id_categoria = c.id_categoria 
WHERE p.estado = 'activo'");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand">Panel Cliente</a>
        <a href="../logout.php" class="btn btn-danger">Cerrar sesion</a>
    </div>
</nav>

<div class="container mt-5">
    <h2>Bienvenido: <?php echo $_SESSION['nombre']; ?></h2>
    <p>Esta vista sera ampliada por el Estudiante 3 con carrito, favoritos y compras.</p>

    <h3 class="mt-4">Productos disponibles</h3>

    <div class="row">
        <?php while ($producto = mysqli_fetch_assoc($productos)) { ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body">
                        <h5><?php echo $producto['nombre']; ?></h5>
                        <p><?php echo $producto['descripcion']; ?></p>
                        <p><strong>Marca:</strong> <?php echo $producto['marca']; ?></p>
                        <p><strong>Categoria:</strong> <?php echo $producto['nombre_categoria']; ?></p>
                        <h4 class="text-primary">Bs <?php echo $producto['precio']; ?></h4>
                        <button class="btn btn-outline-danger w-100 mb-2">Agregar a favoritos</button>
                        <button class="btn btn-dark w-100">Agregar al carrito</button>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>