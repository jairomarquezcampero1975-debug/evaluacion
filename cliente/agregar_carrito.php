<?php
require_once "../includes/auth.php";
protegerRuta();
soloCliente();
require_once "../config/conexion.php";
$id_usuario = $_SESSION['id_usuario'];
$id_producto = (int)($_GET['id'] ?? 0);

$stmt = mysqli_prepare($conexion, "SELECT precio, stock FROM producto WHERE id_producto=? AND estado='activo'");
mysqli_stmt_bind_param($stmt, "i", $id_producto);
mysqli_stmt_execute($stmt);
$producto = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$producto || $producto['stock'] <= 0) { header("Location: inicio.php"); exit(); }

$stmt = mysqli_prepare($conexion, "SELECT id_carrito FROM carrito WHERE id_usuario=? AND estado='activo' LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$carrito = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if (!$carrito) {
    $stmt = mysqli_prepare($conexion, "INSERT INTO carrito(id_usuario, estado) VALUES(?, 'activo')");
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);
    $id_carrito = mysqli_insert_id($conexion);
} else { $id_carrito = $carrito['id_carrito']; }

$stmt = mysqli_prepare($conexion, "SELECT cantidad FROM detalle_carrito WHERE id_carrito=? AND id_producto=?");
mysqli_stmt_bind_param($stmt, "ii", $id_carrito, $id_producto);
mysqli_stmt_execute($stmt);
$detalle = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
if ($detalle) {
    $cantidad = min($detalle['cantidad'] + 1, (int)$producto['stock']);
    $subtotal = $cantidad * $producto['precio'];
    $stmt = mysqli_prepare($conexion, "UPDATE detalle_carrito SET cantidad=?, subtotal=? WHERE id_carrito=? AND id_producto=?");
    mysqli_stmt_bind_param($stmt, "idii", $cantidad, $subtotal, $id_carrito, $id_producto);
    mysqli_stmt_execute($stmt);
} else {
    $cantidad = 1; $subtotal = $producto['precio'];
    $stmt = mysqli_prepare($conexion, "INSERT INTO detalle_carrito(id_carrito,id_producto,cantidad,subtotal) VALUES(?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "iiid", $id_carrito, $id_producto, $cantidad, $subtotal);
    mysqli_stmt_execute($stmt);
}
header("Location: inicio.php");
exit();
?>
