<?php
include("../includes/auth.php");
protegerRuta();
soloAdmin();
include("../config/conexion.php");

$usuarios = mysqli_query($conexion,"
SELECT * FROM usuario
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Usuarios</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>Usuarios registrados</h2>
<a href="dashboard.php" class="btn btn-secondary mb-3">Volver</a>

<table class="table table-bordered">
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Correo</th>
<th>Rol</th>
</tr>

<?php while($u = mysqli_fetch_assoc($usuarios)){ ?>
<tr>
<td><?= $u['id_usuario'] ?></td>
<td><?= $u['nombre'] ?></td>
<td><?= $u['correo'] ?></td>
<td><?= $u['rol'] ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>