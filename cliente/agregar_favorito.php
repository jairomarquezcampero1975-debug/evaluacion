<?php
require_once "../includes/auth.php";
protegerRuta();
soloCliente();
require_once "../config/conexion.php";

$id_usuario = $_SESSION['id_usuario'];
$id_producto = (int)($_GET['id'] ?? 0);

/* verificar si ya existe */
$verificar = mysqli_prepare(
    $conexion,
    "SELECT id_favorito 
     FROM favorito 
     WHERE id_usuario=? AND id_producto=?"
);

mysqli_stmt_bind_param($verificar, "ii", $id_usuario, $id_producto);
mysqli_stmt_execute($verificar);
$resultado = mysqli_stmt_get_result($verificar);

if(mysqli_num_rows($resultado) > 0){
    header("Location: inicio.php?favorito=existe");
    exit();
}

/* insertar favorito */
$stmt = mysqli_prepare(
    $conexion,
    "INSERT INTO favorito(id_usuario,id_producto) VALUES(?,?)"
);

mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_producto);
mysqli_stmt_execute($stmt);

header("Location: inicio.php?favorito=agregado");
exit();
?>