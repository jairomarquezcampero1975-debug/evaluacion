<?php
include("../config/conexion.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id = $_GET['id'];

$sql = mysqli_query($conexion,"
SELECT p.*, c.nombre_categoria
FROM producto p
INNER JOIN categoria c ON p.id_categoria = c.id_categoria
WHERE p.id_producto='$id'
");

$producto = mysqli_fetch_assoc($sql);

if(!$producto){
    header("Location: inicio.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?php echo $producto['nombre']; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand">TecnoStore</a>

        <a href="inicio.php" class="btn btn-outline-light">
            ← Volver al inicio
        </a>
    </div>
</nav>

<div class="container mt-5">

    <div class="card shadow-lg border-0">
        <div class="card-body p-5">

            <h1 class="mb-4">
                <?php echo $producto['nombre']; ?>
            </h1>

            <p class="fs-5 text-muted">
                <?php echo $producto['descripcion']; ?>
            </p>

            <hr>

            <div class="row mt-4">

                <div class="col-md-6">
                    <h5>Información del producto</h5>

                    <p><strong>Marca:</strong> <?php echo $producto['marca']; ?></p>
                    <p><strong>Categoría:</strong> <?php echo $producto['nombre_categoria']; ?></p>
                    <p><strong>Stock:</strong> <?php echo $producto['stock']; ?></p>
                    <p><strong>Estado:</strong> <?php echo $producto['estado']; ?></p>
                </div>

                <div class="col-md-6 text-md-end">
                    <h2 class="text-primary">
                        Bs <?php echo $producto['precio']; ?>
                    </h2>
                </div>

            </div>

            <div class="d-grid gap-3 mt-5">

                <a href="agregar_carrito.php?id=<?php echo $producto['id_producto']; ?>"
                   class="btn btn-dark btn-lg">
                   🛒 Agregar al carrito
                </a>

                <a href="agregar_favorito.php?id=<?php echo $producto['id_producto']; ?>"
                   class="btn btn-outline-danger btn-lg">
                   ❤️ Agregar a favoritos
                </a>

            </div>

        </div>
    </div>

</div>

</body>
</html>