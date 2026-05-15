<?php
include("../config/conexion.php");
session_start();

$id_usuario = $_SESSION['id_usuario'];

$sql = mysqli_query($conexion,"
SELECT f.*, p.nombre, p.precio, p.marca
FROM favorito f
INNER JOIN producto p ON f.id_producto = p.id_producto
WHERE f.id_usuario='$id_usuario'
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Favoritos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
<a href="inicio.php" class="btn btn-dark mb-4">← Volver al inicio</a>

<h2>Mis favoritos</h2>

<div class="row">
<?php while($fila=mysqli_fetch_assoc($sql)){ ?>
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-body">
                <h5><?php echo $fila['nombre']; ?></h5>
                <p><?php echo $fila['marca']; ?></p>
                <h4 class="text-primary">Bs <?php echo $fila['precio']; ?></h4>
            </div>
        </div>
    </div>
<?php } ?>
</div>
</div>
</body>
</html>