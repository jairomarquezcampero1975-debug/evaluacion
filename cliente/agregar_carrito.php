<?php
include("../config/conexion.php");
session_start();

$id_usuario = $_SESSION['id_usuario'];
$id_producto = $_GET['id'];

$buscar = mysqli_query($conexion,"
SELECT * FROM carrito
WHERE id_usuario='$id_usuario' AND estado='activo'
");

if(mysqli_num_rows($buscar)==0){
    mysqli_query($conexion,"
    INSERT INTO carrito(id_usuario)
    VALUES('$id_usuario')
    ");
    
    $id_carrito = mysqli_insert_id($conexion);
}else{
    $carrito = mysqli_fetch_assoc($buscar);
    $id_carrito = $carrito['id_carrito'];
}

$producto = mysqli_query($conexion,"
SELECT * FROM producto WHERE id_producto='$id_producto'
");
$p = mysqli_fetch_assoc($producto);

$precio = $p['precio'];

mysqli_query($conexion,"
INSERT INTO detalle_carrito(id_carrito,id_producto,cantidad,subtotal)
VALUES('$id_carrito','$id_producto',1,'$precio')
");

header("Location: inicio.php?mensaje=carrito");
?>