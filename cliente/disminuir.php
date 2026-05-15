<?php
include("../config/conexion.php");

$id = $_GET['id'];

mysqli_query($conexion,"
UPDATE detalle_carrito
SET cantidad = cantidad - 1,
subtotal = subtotal - (
    SELECT precio
    FROM producto
    WHERE id_producto = detalle_carrito.id_producto
)
WHERE id_detalle_carrito='$id'
AND cantidad > 1
");
?>