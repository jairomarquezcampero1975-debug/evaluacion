<?php
require_once "../includes/auth.php";
protegerRuta();
soloCliente();
require_once "../config/conexion.php";
$id_usuario = $_SESSION['id_usuario'];
$id_producto = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($conexion, "SELECT dc.cantidad, p.precio, p.stock, dc.id_detalle_carrito FROM carrito c INNER JOIN detalle_carrito dc ON c.id_carrito=dc.id_carrito INNER JOIN producto p ON dc.id_producto=p.id_producto WHERE c.id_usuario=? AND c.estado='activo' AND dc.id_producto=?");
mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_producto);
mysqli_stmt_execute($stmt);
$d = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if ($d && $d['cantidad'] < $d['stock']) {
    $cantidad = $d['cantidad'] + 1;
    $subtotal = $cantidad * $d['precio'];
    $stmt = mysqli_prepare($conexion, "UPDATE detalle_carrito SET cantidad=?, subtotal=? WHERE id_detalle_carrito=?");
    mysqli_stmt_bind_param($stmt, "idi", $cantidad, $subtotal, $d['id_detalle_carrito']);
    mysqli_stmt_execute($stmt);
}
header("Location: inicio.php"); exit();
?>
