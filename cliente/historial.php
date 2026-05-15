<?php
include("../config/conexion.php");
session_start();

$id_usuario = $_SESSION['id_usuario'];

$sql = mysqli_query($conexion,"
SELECT * FROM venta
WHERE id_usuario='$id_usuario'
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Historial</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
<a href="inicio.php" class="btn btn-dark mb-4">← Volver al inicio</a>

<h2>Historial de compras</h2>

<table class="table table-hover shadow mt-4">
<tr>
    <th>ID</th>
    <th>Total</th>
    <th>Fecha</th>
    <th>Estado</th>
</tr>

<?php while($fila=mysqli_fetch_assoc($sql)){ ?>
<tr>
    <td><?php echo $fila['id_venta']; ?></td>
    <td>Bs <?php echo $fila['total']; ?></td>
    <td><?php echo $fila['fecha']; ?></td>
    <td>
        <span class="badge bg-success">
            <?php echo $fila['estado_venta']; ?>
        </span>
    </td>
</tr>
<?php } ?>
</table>

</div>
</body>
</html>