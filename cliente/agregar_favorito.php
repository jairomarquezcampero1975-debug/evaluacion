<?php
include("../config/conexion.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['id_usuario'];
$id_producto = $_GET['id'];

$verificar = mysqli_query($conexion,"
SELECT * FROM favorito
WHERE id_usuario='$id_usuario'
AND id_producto='$id_producto'
");

if(mysqli_num_rows($verificar) == 0){

    mysqli_query($conexion,"
    INSERT INTO favorito(id_usuario,id_producto)
    VALUES('$id_usuario','$id_producto')
    ");
}

header("Location: inicio.php");
?>