<?php
include("../config/conexion.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_usuario = $_SESSION['id_usuario'];

$sql = mysqli_query($conexion,"
SELECT dc.*, p.nombre
FROM detalle_carrito dc
INNER JOIN producto p ON dc.id_producto = p.id_producto
INNER JOIN carrito c ON dc.id_carrito = c.id_carrito
WHERE c.id_usuario='$id_usuario' AND c.estado='activo'
");

$total = 0;
?>

<div class="modal fade" id="carritoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Mi carrito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="contenidoCarrito">

                <table class="table table-hover">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>

                    <?php while($fila = mysqli_fetch_assoc($sql)){ 
                        $total += $fila['subtotal'];
                    ?>
                    <tr>
                        <td><?php echo $fila['nombre']; ?></td>

                        <td>
                            <button onclick="actualizarCarrito('disminuir.php?id=<?php echo $fila['id_detalle_carrito']; ?>')" 
                            class="btn btn-sm btn-secondary">-</button>

                            <span class="mx-2"><?php echo $fila['cantidad']; ?></span>

                            <button onclick="actualizarCarrito('aumentar.php?id=<?php echo $fila['id_detalle_carrito']; ?>')" 
                            class="btn btn-sm btn-secondary">+</button>
                        </td>

                        <td>Bs <?php echo $fila['subtotal']; ?></td>

                        <td>
                            <button onclick="actualizarCarrito('eliminar_carrito.php?id=<?php echo $fila['id_detalle_carrito']; ?>')" 
                            class="btn btn-danger btn-sm">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                </table>

                <h4 class="text-end mt-3">Total: Bs <?php echo $total; ?></h4>
            </div>

            <div class="modal-footer">
                <?php if($total > 0){ ?>
                    <a href="confirmar_compra.php" class="btn btn-success">
                        Confirmar compra
                    </a>
                <?php } else { ?>
                    <button class="btn btn-secondary" disabled>
                        Carrito vacío
                    </button>
                <?php } ?>
            </div>

        </div>
    </div>
</div>