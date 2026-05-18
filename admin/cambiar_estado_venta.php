<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";
$id_venta = (int)($_POST['id_venta'] ?? 0);
$estado = limpiar($_POST['estado_venta'] ?? 'pendiente');
$permitidos = ['pendiente','pagado','cancelado'];
if (!in_array($estado, $permitidos)) { $estado = 'pendiente'; }
$stmt = mysqli_prepare($conexion, "UPDATE venta SET estado_venta=? WHERE id_venta=?");
mysqli_stmt_bind_param($stmt, "si", $estado, $id_venta);
mysqli_stmt_execute($stmt);
header("Location: ventas.php");
exit();
?>
