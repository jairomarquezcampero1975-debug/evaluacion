<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/conexion.php";

$id_usuario = $_SESSION['id_usuario'] ?? 0;
$total = 0;
$items = [];

if ($id_usuario) {
    $stmt = mysqli_prepare($conexion, "SELECT dc.*, p.nombre, p.precio, p.imagen, p.stock FROM carrito c INNER JOIN detalle_carrito dc ON c.id_carrito = dc.id_carrito INNER JOIN producto p ON dc.id_producto = p.id_producto WHERE c.id_usuario = ? AND c.estado = 'activo' ORDER BY dc.id_detalle_carrito DESC");
    mysqli_stmt_bind_param($stmt, "i", $id_usuario);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($res)) {
        $items[] = $row;
        $total += $row['subtotal'];
    }
}
?>

<div class="modal fade" id="carritoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Carrito de compras</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="carritoMensaje" class="d-none alert"></div>

                <?php if (count($items) === 0) { ?>

                    <div class="alert alert-info mb-0">Tu carrito esta vacio.</div>

                <?php } else { ?>

                    <div id="carritoContenido">
                        <div class="table-responsive">
                            <table class="table align-middle carrito-tabla">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th>Subtotal</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>

                                <tbody id="carritoBody">
                                    <?php foreach ($items as $item) { ?>
                                        <tr id="fila-carrito-<?php echo (int)$item['id_producto']; ?>">
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <img class="product-img-sm" src="<?php echo e(imagenProducto($item['imagen'], '../')); ?>">
                                                    <div>
                                                        <span class="fw-semibold"><?php echo e($item['nombre']); ?></span><br>
                                                        <small class="text-muted">Stock disponible: <?php echo (int)$item['stock']; ?></small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="input-group input-group-sm carrito-cantidad mx-auto">
                                                    <button type="button" class="btn btn-outline-primary" onclick="actualizarCarritoDirecto(<?php echo (int)$item['id_producto']; ?>, 'disminuir')">-</button>

                                                    <input type="number"
                                                           class="form-control text-center"
                                                           id="cantidad-<?php echo (int)$item['id_producto']; ?>"
                                                           value="<?php echo (int)$item['cantidad']; ?>"
                                                           min="1"
                                                           max="<?php echo (int)$item['stock']; ?>"
                                                           data-stock="<?php echo (int)$item['stock']; ?>"
                                                           onchange="colocarCantidadDirecta(<?php echo (int)$item['id_producto']; ?>)"
                                                           onkeydown="if(event.key === 'Enter'){ event.preventDefault(); this.blur(); }">

                                                    <button type="button" class="btn btn-outline-primary" onclick="actualizarCarritoDirecto(<?php echo (int)$item['id_producto']; ?>, 'aumentar')">+</button>
                                                </div>
                                            </td>

                                            <td class="fw-semibold" id="subtotal-<?php echo (int)$item['id_producto']; ?>">
                                                Bs <?php echo number_format($item['subtotal'], 2); ?>
                                            </td>

                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="actualizarCarritoDirecto(<?php echo (int)$item['id_producto']; ?>, 'eliminar')">Eliminar</button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <h4 class="text-end">
                            Total: Bs <span id="totalCarrito"><?php echo number_format($total, 2); ?></span>
                        </h4>
                    </div>

                <?php } ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                <?php if (count($items) > 0) { ?>
                    <a id="btnConfirmarCompra" href="confirmar_compra.php" class="btn btn-primary">Confirmar compra</a>
                <?php } ?>
            </div>

        </div>
    </div>
</div>

<script>
function mensajeCarrito(texto, tipo) {
    const mensaje = document.getElementById('carritoMensaje');

    if (!mensaje) {
        return;
    }

    mensaje.textContent = texto;
    mensaje.className = 'alert alert-' + tipo;

    setTimeout(function () {
        mensaje.className = 'd-none alert';
    }, 1800);
}

function actualizarCarritoDirecto(idProducto, accion, cantidad = null) {
    const datos = new FormData();
    datos.append('id_producto', idProducto);
    datos.append('accion', accion);

    if (cantidad !== null) {
        datos.append('cantidad', cantidad);
    }

    fetch('actualizar_carrito.php', {
        method: 'POST',
        body: datos
    })
    .then(function(respuesta) {
        return respuesta.json();
    })
    .then(function(data) {
        if (!data.ok) {
            mensajeCarrito(data.mensaje, 'danger');
            return;
        }

        const fila = document.getElementById('fila-carrito-' + idProducto);
        const input = document.getElementById('cantidad-' + idProducto);
        const subtotal = document.getElementById('subtotal-' + idProducto);
        const total = document.getElementById('totalCarrito');

        if (data.eliminado) {
            if (fila) {
                fila.remove();
            }
        } else {
            if (input) {
                input.value = data.cantidad;
            }

            if (subtotal) {
                subtotal.textContent = 'Bs ' + parseFloat(data.subtotal).toFixed(2);
            }
        }

        if (total) {
            total.textContent = parseFloat(data.total).toFixed(2);
        }

        const filas = document.querySelectorAll('#carritoBody tr');
        const contenido = document.getElementById('carritoContenido');
        const botonConfirmar = document.getElementById('btnConfirmarCompra');

        if (filas.length === 0 && contenido) {
            contenido.innerHTML = '<div class="alert alert-info mb-0">Tu carrito esta vacio.</div>';

            if (botonConfirmar) {
                botonConfirmar.remove();
            }
        }

        mensajeCarrito(data.mensaje, 'success');
    })
    .catch(function() {
        mensajeCarrito('Error al actualizar el carrito.', 'danger');
    });
}

function colocarCantidadDirecta(idProducto) {
    const input = document.getElementById('cantidad-' + idProducto);

    if (!input) {
        return;
    }

    let cantidad = parseInt(input.value);
    const stock = parseInt(input.getAttribute('data-stock'));

    if (isNaN(cantidad) || cantidad < 1) {
        cantidad = 1;
    }

    if (cantidad > stock) {
        cantidad = stock;
        mensajeCarrito('Solo hay ' + stock + ' unidades disponibles.', 'warning');
    }

    input.value = cantidad;

    actualizarCarritoDirecto(idProducto, 'set', cantidad);
}
</script>