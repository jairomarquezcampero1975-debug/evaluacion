<?php
require_once "../includes/auth.php";
protegerRuta();
soloCliente();
require_once "../config/conexion.php";

header('Content-Type: application/json; charset=utf-8');

$id_usuario = $_SESSION['id_usuario'];
$id_producto = (int)($_POST['id_producto'] ?? 0);
$accion = $_POST['accion'] ?? '';
$cantidad_nueva = (int)($_POST['cantidad'] ?? 0);

$respuesta = [
    'ok' => false,
    'mensaje' => 'No se pudo actualizar el carrito.',
    'cantidad' => 0,
    'subtotal' => '0.00',
    'total' => '0.00',
    'eliminado' => false
];

$stmt = mysqli_prepare($conexion, "SELECT dc.id_detalle_carrito, dc.cantidad, p.precio, p.stock FROM carrito c INNER JOIN detalle_carrito dc ON c.id_carrito = dc.id_carrito INNER JOIN producto p ON dc.id_producto = p.id_producto WHERE c.id_usuario = ? AND c.estado = 'activo' AND dc.id_producto = ?");
mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_producto);
mysqli_stmt_execute($stmt);
$detalle = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$detalle) {
    echo json_encode($respuesta);
    exit();
}

$id_detalle = (int)$detalle['id_detalle_carrito'];
$cantidad = (int)$detalle['cantidad'];
$precio = (float)$detalle['precio'];
$stock = (int)$detalle['stock'];

if ($accion === 'eliminar') {
    $stmt = mysqli_prepare($conexion, "DELETE FROM detalle_carrito WHERE id_detalle_carrito = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_detalle);
    mysqli_stmt_execute($stmt);

    $stmt = mysqli_prepare($conexion, "SELECT IFNULL(SUM(dc.subtotal), 0) AS total FROM carrito c INNER JOIN detalle_carrito dc ON c.id_carrito = dc.id_carrito WHERE c.id_usuario = ? AND c.estado = 'activo'");
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);
    $total_row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    echo json_encode([
        'ok' => true,
        'mensaje' => 'Producto eliminado del carrito.',
        'cantidad' => 0,
        'subtotal' => '0.00',
        'total' => number_format($total_row['total'], 2, '.', ''),
        'eliminado' => true
    ]);
    exit();
}

if ($accion === 'aumentar') {
    $cantidad++;
}

if ($accion === 'disminuir') {
    $cantidad--;
}

if ($accion === 'set') {
    $cantidad = $cantidad_nueva;
}

if ($cantidad < 1) {
    $cantidad = 1;
}

$mensaje = 'Carrito actualizado.';

if ($cantidad > $stock) {
    $cantidad = $stock;
    $mensaje = 'Solo hay ' . $stock . ' unidades disponibles.';
}

$subtotal = $cantidad * $precio;

$stmt = mysqli_prepare($conexion, "UPDATE detalle_carrito SET cantidad = ?, subtotal = ? WHERE id_detalle_carrito = ?");
mysqli_stmt_bind_param($stmt, "idi", $cantidad, $subtotal, $id_detalle);
mysqli_stmt_execute($stmt);

$stmt = mysqli_prepare($conexion, "SELECT IFNULL(SUM(dc.subtotal), 0) AS total FROM carrito c INNER JOIN detalle_carrito dc ON c.id_carrito = dc.id_carrito WHERE c.id_usuario = ? AND c.estado = 'activo'");
mysqli_stmt_bind_param($stmt, "i", $id_usuario);
mysqli_stmt_execute($stmt);
$total_row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

echo json_encode([
    'ok' => true,
    'mensaje' => $mensaje,
    'cantidad' => $cantidad,
    'subtotal' => number_format($subtotal, 2, '.', ''),
    'total' => number_format($total_row['total'], 2, '.', ''),
    'eliminado' => false
]);
exit();
?>