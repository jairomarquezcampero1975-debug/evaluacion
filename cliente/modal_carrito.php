<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/conexion.php";
$id_usuario = $_SESSION['id_usuario'] ?? 0;
$total = 0;
$items = [];
if ($id_usuario) {
    $stmt = mysqli_prepare($conexion, "SELECT dc.*, p.nombre, p.precio, p.imagen, p.stock FROM carrito c INNER JOIN detalle_carrito dc ON c.id_carrito=dc.id_carrito INNER JOIN producto p ON dc.id_producto=p.id_producto WHERE c.id_usuario=? AND c.estado='activo'");
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) { $items[] = $row; $total += $row['subtotal']; }
}
?>
<div class="modal fade" id="carritoModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Carrito de compras</h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><?php if (count($items)===0) { ?><div class="alert alert-info">Tu carrito esta vacio.</div><?php } else { ?><div class="table-responsive"><table class="table"><tr><th>Producto</th><th>Cantidad</th><th>Subtotal</th><th>Accion</th></tr><?php foreach($items as $item){ ?><tr><td><div class="d-flex align-items-center gap-2"><img class="product-img-sm" src="<?php echo e(imagenProducto($item['imagen'], '../')); ?>"><span><?php echo e($item['nombre']); ?></span></div></td><td><a href="disminuir.php?id=<?php echo $item['id_producto']; ?>" class="btn btn-sm btn-outline-primary">-</a> <strong><?php echo $item['cantidad']; ?></strong> <a href="aumentar.php?id=<?php echo $item['id_producto']; ?>" class="btn btn-sm btn-outline-primary">+</a></td><td>Bs <?php echo number_format($item['subtotal'],2); ?></td><td><a href="eliminar_carrito.php?id=<?php echo $item['id_producto']; ?>" class="btn btn-danger btn-sm">Eliminar</a></td></tr><?php } ?></table></div><h4 class="text-end">Total: Bs <?php echo number_format($total,2); ?></h4><?php } ?></div><div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button><?php if (count($items)>0) { ?><a href="confirmar_compra.php" class="btn btn-primary">Confirmar compra</a><?php } ?></div></div></div></div>
