<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";

$id = intval($_GET['id']);
$accion = $_GET['accion'];

if($accion=="banear"){
    mysqli_query($conexion,"
    UPDATE usuario
    SET estado='baneado'
    WHERE id_usuario=$id
    ");
}

if($accion=="activar"){
    mysqli_query($conexion,"
    UPDATE usuario
    SET estado='activo'
    WHERE id_usuario=$id
    ");
}

header("Location: usuarios.php");
exit;
?>