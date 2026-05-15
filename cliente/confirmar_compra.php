<?php
include("../config/conexion.php");
session_start();

$id_usuario = $_SESSION['id_usuario'];

$carrito = mysqli_query($conexion,"
SELECT * FROM carrito
WHERE id_usuario='$id_usuario' AND estado='activo'
");

if(mysqli_num_rows($carrito)==0){
    header("Location: inicio.php");
    exit();
}

$c = mysqli_fetch_assoc($carrito);
$id_carrito = $c['id_carrito'];

$detalles = mysqli_query($conexion,"
SELECT * FROM detalle_carrito
WHERE id_carrito='$id_carrito'
");

$total = 0;

while($d=mysqli_fetch_assoc($detalles)){
    $total += $d['subtotal'];
}

if($total <= 0){
    header("Location: inicio.php");
    exit();
}

mysqli_query($conexion,"
INSERT INTO venta(id_usuario,total,estado_venta)
VALUES('$id_usuario','$total','pagado')
");

$id_venta = mysqli_insert_id($conexion);

$detalles2 = mysqli_query($conexion,"
SELECT * FROM detalle_carrito
WHERE id_carrito='$id_carrito'
");

while($d=mysqli_fetch_assoc($detalles2)){

    mysqli_query($conexion,"
    INSERT INTO detalle_venta(id_venta,id_producto,cantidad,subtotal)
    VALUES(
        '$id_venta',
        '{$d['id_producto']}',
        '{$d['cantidad']}',
        '{$d['subtotal']}'
    )
    ");

    mysqli_query($conexion,"
    UPDATE producto
    SET stock = stock - {$d['cantidad']}
    WHERE id_producto = {$d['id_producto']}
    ");
}

mysqli_query($conexion,"
UPDATE carrito
SET estado='comprado'
WHERE id_carrito='$id_carrito'
");

header("Location: historial.php");
?>