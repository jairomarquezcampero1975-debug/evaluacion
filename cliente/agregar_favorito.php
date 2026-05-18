<?php
require_once "../includes/auth.php";
protegerRuta();
soloCliente();
require_once "../config/conexion.php";
$id_usuario = $_SESSION['id_usuario'];
$id_producto = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conexion, "INSERT IGNORE INTO favorito(id_usuario,id_producto) VALUES(?,?)");
mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_producto);
mysqli_stmt_execute($stmt);
header("Location: favoritos.php"); exit();
?>
