<?php
include("../config/conexion.php");

$id = $_GET['id'];

mysqli_query($conexion,"
DELETE FROM detalle_carrito
WHERE id_detalle_carrito='$id'
");
?>