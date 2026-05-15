<?php
include("../includes/auth.php");
protegerRuta();
soloAdmin();
include("../config/conexion.php");

$ventas = mysqli_query($conexion,"
SELECT venta.*, usuario.nombre
FROM venta
INNER JOIN usuario
ON venta.id_usuario=usuario.id_usuario
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Ventas</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>Ventas</h2>
<a href="dashboard.php" class="btn btn-secondary mb-3">Volver</a>

<table class="table table-bordered">
<tr>
<th>ID</th>
<th>Cliente</th>
<th>Total</th>
<th>Estado</th>
<th>Cambiar</th>
</tr>

<?php while($v = mysqli_fetch_assoc($ventas)){ ?>
<tr>
<td><?= $v['id_venta'] ?></td>
<td><?= $v['nombre'] ?></td>
<td><?= $v['total'] ?></td>
<td><?= $v['estado_venta'] ?></td>
<td>
<a href="cambiar_estado_venta.php?id=<?= $v['id_venta'] ?>" class="btn btn-warning btn-sm">
Cambiar
</a>
</td>
</tr>
<?php } ?>

</table>

</body>
</html>