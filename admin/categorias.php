<?php
include("../includes/auth.php");
protegerRuta();
soloAdmin();
include("../config/conexion.php");

/* AGREGAR */
if(isset($_POST['agregar'])){
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    mysqli_query($conexion,"
        INSERT INTO categoria(nombre_categoria, descripcion)
        VALUES('$nombre','$descripcion')
    ");
}

/* ELIMINAR */
if(isset($_GET['eliminar'])){
    $id = $_GET['eliminar'];

    mysqli_query($conexion,"
        DELETE FROM categoria
        WHERE id_categoria=$id
    ");
}

$categorias = mysqli_query($conexion,"
    SELECT * FROM categoria
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Categorías</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>Categorías</h2>
<a href="dashboard.php" class="btn btn-secondary mb-3">Volver</a>

<form method="POST" class="mb-4">
    <input type="text" name="nombre" placeholder="Nombre" required class="form-control mb-2">
    <textarea name="descripcion" placeholder="Descripción" class="form-control mb-2"></textarea>
    <button name="agregar" class="btn btn-primary">Agregar categoría</button>
</form>

<table class="table table-bordered">
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Descripción</th>
<th>Acción</th>
</tr>

<?php while($cat = mysqli_fetch_assoc($categorias)){ ?>
<tr>
<td><?= $cat['id_categoria'] ?></td>
<td><?= $cat['nombre_categoria'] ?></td>
<td><?= $cat['descripcion'] ?></td>
<td>
<a href="?eliminar=<?= $cat['id_categoria'] ?>" class="btn btn-danger btn-sm">
Eliminar
</a>
</td>
</tr>
<?php } ?>

</table>

</body>
</html>