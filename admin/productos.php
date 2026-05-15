<?php
include("../includes/auth.php");
protegerRuta();
soloAdmin();
include("../config/conexion.php");

/* AGREGAR PRODUCTO */
if(isset($_POST['agregar'])){
    $categoria = $_POST['categoria'];
    $nombre = $_POST['nombre'];
    $marca = $_POST['marca'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    mysqli_query($conexion,"
        INSERT INTO producto(
            id_categoria,nombre,marca,precio,stock
        )
        VALUES(
            '$categoria','$nombre','$marca','$precio','$stock'
        )
    ");
}

/* ELIMINAR */
if(isset($_GET['eliminar'])){
    $id = $_GET['eliminar'];

    mysqli_query($conexion,"
        DELETE FROM producto
        WHERE id_producto=$id
    ");
}

$categorias = mysqli_query($conexion,"
    SELECT * FROM categoria
");

$productos = mysqli_query($conexion,"
SELECT producto.*, categoria.nombre_categoria
FROM producto
INNER JOIN categoria
ON producto.id_categoria=categoria.id_categoria
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Productos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>Productos</h2>
<a href="dashboard.php" class="btn btn-secondary mb-3">Volver</a>

<form method="POST" class="mb-4">
<select name="categoria" class="form-control mb-2">
<?php while($c = mysqli_fetch_assoc($categorias)){ ?>
<option value="<?= $c['id_categoria'] ?>">
<?= $c['nombre_categoria'] ?>
</option>
<?php } ?>
</select>

<input type="text" name="nombre" placeholder="Nombre" required class="form-control mb-2">
<input type="text" name="marca" placeholder="Marca" class="form-control mb-2">
<input type="number" name="precio" placeholder="Precio" required class="form-control mb-2">
<input type="number" name="stock" placeholder="Stock" required class="form-control mb-2">

<button name="agregar" class="btn btn-primary">
Agregar producto
</button>
</form>

<table class="table table-bordered">
<tr>
<th>Nombre</th>
<th>Categoría</th>
<th>Precio</th>
<th>Stock</th>
<th>Acción</th>
</tr>

<?php while($p = mysqli_fetch_assoc($productos)){ ?>
<tr>
<td><?= $p['nombre'] ?></td>
<td><?= $p['nombre_categoria'] ?></td>
<td><?= $p['precio'] ?></td>
<td><?= $p['stock'] ?></td>
<td>
<a href="?eliminar=<?= $p['id_producto'] ?>" class="btn btn-danger btn-sm">
Eliminar
</a>
</td>
</tr>
<?php } ?>
</table>

</body>
</html>