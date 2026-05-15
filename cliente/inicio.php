<?php
include("../includes/auth.php");
protegerRuta();
soloCliente();
include("../config/conexion.php");

$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$marca = isset($_GET['marca']) ? $_GET['marca'] : '';

$query = "SELECT p.*, c.nombre_categoria
FROM producto p
INNER JOIN categoria c ON p.id_categoria = c.id_categoria
WHERE p.estado='activo'";

if($buscar != ''){
    $query .= " AND (
        p.nombre LIKE '%$buscar%' OR
        p.marca LIKE '%$buscar%'
    )";
}

if($categoria != ''){
    $query .= " AND c.id_categoria='$categoria'";
}

if($marca != ''){
    $query .= " AND p.marca='$marca'";
}

$productos = mysqli_query($conexion, $query);
$categorias = mysqli_query($conexion,"SELECT * FROM categoria");
$marcas = mysqli_query($conexion,"SELECT DISTINCT marca FROM producto");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Cliente</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark shadow">
    <div class="container">
        <a class="navbar-brand fw-bold">TecnoStore</a>

        <div>
            <a href="favoritos.php" class="btn btn-outline-light me-2">Favoritos</a>
            <a href="historial.php" class="btn btn-outline-info me-2">Historial</a>
            <a href="../logout.php" class="btn btn-danger">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-5">

    <h2 class="mb-4">Bienvenido, <?php echo $_SESSION['nombre']; ?></h2>

    <form method="GET" class="row g-3 mb-5">

        <div class="col-md-4">
            <input type="text"
                   name="buscar"
                   class="form-control"
                   placeholder="Buscar producto o marca">
        </div>

        <div class="col-md-3">
            <select name="categoria" class="form-select">
                <option value="">Todas categorías</option>

                <?php while($cat=mysqli_fetch_assoc($categorias)){ ?>
                    <option value="<?php echo $cat['id_categoria']; ?>">
                        <?php echo $cat['nombre_categoria']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-3">
            <select name="marca" class="form-select">
                <option value="">Todas marcas</option>

                <?php while($m=mysqli_fetch_assoc($marcas)){ ?>
                    <option value="<?php echo $m['marca']; ?>">
                        <?php echo $m['marca']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">
                Filtrar
            </button>
        </div>

    </form>

    <div class="row">
        <?php while($producto=mysqli_fetch_assoc($productos)){ ?>

        <div class="col-md-4 mb-4">
            <div class="card shadow border-0 h-100">

                <div class="card-body p-4">

                    <h5 class="mb-3">
                        <?php echo $producto['nombre']; ?>
                    </h5>

                    <p class="text-muted">
                        <?php echo $producto['descripcion']; ?>
                    </p>

                    <p><strong>Marca:</strong> <?php echo $producto['marca']; ?></p>
                    <p><strong>Categoría:</strong> <?php echo $producto['nombre_categoria']; ?></p>

                    <p>
                        <span class="badge bg-success">
                            Stock: <?php echo $producto['stock']; ?>
                        </span>
                    </p>

                    <h3 class="text-primary mb-4">
                        Bs <?php echo $producto['precio']; ?>
                    </h3>

                    <div class="d-grid gap-2">

                        <a href="ver_producto.php?id=<?php echo $producto['id_producto']; ?>"
                           class="btn btn-primary">
                           Ver producto
                        </a>

                        <a href="agregar_favorito.php?id=<?php echo $producto['id_producto']; ?>"
                           class="btn btn-outline-danger">
                           ❤️ Favorito
                        </a>

                        <a href="agregar_carrito.php?id=<?php echo $producto['id_producto']; ?>"
                           class="btn btn-dark">
                           🛒 Agregar carrito
                        </a>

                    </div>

                </div>
            </div>
        </div>

        <?php } ?>
    </div>
</div>

<button class="btn btn-primary rounded-circle position-fixed shadow"
        style="bottom:30px; right:30px; width:70px; height:70px;"
        data-bs-toggle="modal"
        data-bs-target="#carritoModal">
    🛒
</button>

<?php include("modal_carrito.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<script>
function actualizarCarrito(url){
    fetch(url)
    .then(response => response.text())
    .then(() => {
        recargarCarrito();
    });
}

function recargarCarrito(){
    fetch('modal_carrito.php')
    .then(response => response.text())
    .then(data => {
        const parser = new DOMParser();
        const html = parser.parseFromString(data, 'text/html');

        document.getElementById('carritoModal').innerHTML =
            html.getElementById('carritoModal').innerHTML;
    });
}
</script>

</body>
</html>