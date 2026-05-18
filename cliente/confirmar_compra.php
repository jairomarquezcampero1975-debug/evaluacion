<?php
require_once "../includes/auth.php";
protegerRuta();
soloCliente();
require_once "../config/conexion.php";
$id_usuario = $_SESSION['id_usuario'];

mysqli_begin_transaction($conexion);
try {
    $stmt = mysqli_prepare($conexion, "SELECT id_carrito FROM carrito WHERE id_usuario=? AND estado='activo' LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);
    $carrito = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if (!$carrito) { throw new Exception("carrito_vacio"); }
    $id_carrito = $carrito['id_carrito'];

    $stmt = mysqli_prepare($conexion, "SELECT dc.*, p.precio, p.stock FROM detalle_carrito dc INNER JOIN producto p ON dc.id_producto=p.id_producto WHERE dc.id_carrito=?");
    mysqli_stmt_bind_param($stmt, "i", $id_carrito);
    mysqli_stmt_execute($stmt);
    $detalles = mysqli_stmt_get_result($stmt);
    $items = [];
    $total = 0;
    while ($d = mysqli_fetch_assoc($detalles)) {
        if ($d['cantidad'] > $d['stock']) { throw new Exception("stock_insuficiente"); }
        $subtotal = $d['cantidad'] * $d['precio'];
        $items[] = [$d['id_producto'], $d['cantidad'], $subtotal];
        $total += $subtotal;
    }
    if ($total <= 0 || count($items) === 0) { throw new Exception("carrito_vacio"); }

    $estado = 'pagado';
    $stmt = mysqli_prepare($conexion, "INSERT INTO venta(id_usuario,total,estado_venta) VALUES(?,?,?)");
    mysqli_stmt_bind_param($stmt, "ids", $id_usuario, $total, $estado);
    mysqli_stmt_execute($stmt);
    $id_venta = mysqli_insert_id($conexion);

    foreach ($items as $item) {
        [$id_producto, $cantidad, $subtotal] = $item;
        $stmt = mysqli_prepare($conexion, "INSERT INTO detalle_venta(id_venta,id_producto,cantidad,subtotal) VALUES(?,?,?,?)");
        mysqli_stmt_bind_param($stmt, "iiid", $id_venta, $id_producto, $cantidad, $subtotal);
        mysqli_stmt_execute($stmt);
        $stmt = mysqli_prepare($conexion, "UPDATE producto SET stock=stock-? WHERE id_producto=?");
        mysqli_stmt_bind_param($stmt, "ii", $cantidad, $id_producto);
        mysqli_stmt_execute($stmt);
    }

    $stmt = mysqli_prepare($conexion, "UPDATE carrito SET estado='comprado' WHERE id_carrito=?");
    mysqli_stmt_bind_param($stmt, "i", $id_carrito);
    mysqli_stmt_execute($stmt);
    mysqli_commit($conexion);
    header("Location: historial.php?compra=ok");
    exit();
} catch (Exception $e) {
    mysqli_rollback($conexion);
    header("Location: inicio.php?error=" . $e->getMessage());
    exit();
}
?>
