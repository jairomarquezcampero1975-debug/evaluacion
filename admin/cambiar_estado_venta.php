<?php
include("../includes/auth.php");
protegerRuta();
soloAdmin();
include("../config/conexion.php");

$id = $_GET['id'];

mysqli_query($conexion,"
UPDATE venta
SET estado_venta='pagado'
WHERE id_venta=$id
");

header("Location: ventas.php");
?>