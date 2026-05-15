<?php
include("../includes/auth.php");
protegerRuta();
soloAdmin();
include("../config/conexion.php");

$stock = mysqli_query($conexion,"
SELECT * FROM producto
WHERE stock <= 5
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Control de stock</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>Productos con stock bajo</h2>
<a href="dashboard.php" class="btn btn-secondary mb-3">Volver</a>

<table class="table table-bordered">
<tr>
<th>Producto</th>
<th>Stock</th>
</tr>

<?php while($p = mysqli_fetch_assoc($stock)){ ?>
<tr>
<td><?= $p['nombre'] ?></td>
<td><?= $p['stock'] ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>