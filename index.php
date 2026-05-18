<?php
require_once "config/conexion.php";
require_once "includes/auth.php";
require_once "includes/header.php";
require_once "includes/navbar.php";

$productos = mysqli_query($conexion, "SELECT p.*, c.nombre_categoria FROM producto p INNER JOIN categoria c ON p.id_categoria=c.id_categoria WHERE p.estado='activo' ORDER BY p.id_producto DESC LIMIT 6");
$categorias = mysqli_query($conexion, "SELECT * FROM categoria ORDER BY nombre_categoria ASC");
?>
<section class="banner">
    <div class="container text-center text-white">
        <h1 class="display-4 fw-bold">Venta de Celulares y Accesorios</h1>
        <p class="lead">Encuentra celulares, accesorios, audifonos, cargadores y smartwatch al mejor precio.</p>
        <a href="registro.php" class="btn btn-primary btn-lg me-2">Comprar ahora</a>
        <a href="#productos" class="btn btn-outline-light btn-lg">Ver productos</a>
    </div>
</section>

<section class="container my-5" id="productos">
    <h2 class="section-title text-center mb-4">Productos destacados</h2>
    <div class="row g-4">
        <?php while ($producto = mysqli_fetch_assoc($productos)) { ?>
            <div class="col-md-4">
                <div class="card h-100 producto-card">
                    <img class="product-img" src="<?php echo e(imagenProducto($producto['imagen'])); ?>" alt="<?php echo e($producto['nombre']); ?>">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-primary align-self-start mb-3"><?php echo e($producto['nombre_categoria']); ?></span>
                        <h5 class="card-title"><?php echo e($producto['nombre']); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo e($producto['descripcion']); ?></p>
                        <p class="mb-1"><strong>Marca:</strong> <?php echo e($producto['marca']); ?></p>
                        <h4 class="text-primary mb-3">Bs <?php echo number_format($producto['precio'], 2); ?></h4>
                        <a href="login.php" class="btn btn-dark w-100">Iniciar sesion para comprar</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<section class="bg-white py-5" id="categorias">
    <div class="container">
        <h2 class="section-title text-center mb-4">Categorias</h2>
        <div class="row g-4">
            <?php while ($categoria = mysqli_fetch_assoc($categorias)) { ?>
                <div class="col-md-3">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <h5><?php echo e($categoria['nombre_categoria']); ?></h5>
                            <p><?php echo e($categoria['descripcion']); ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="container my-5" id="beneficios">
    <h2 class="section-title text-center mb-4">Beneficios de nuestra tienda</h2>
    <div class="row g-4 text-center">
        <div class="col-md-4"><div class="card h-100"><div class="card-body"><h4>Compra segura</h4><p>Login con password_hash, password_verify, sesiones, roles y 2FA simulado.</p></div></div></div>
        <div class="col-md-4"><div class="card h-100"><div class="card-body"><h4>Gestion completa</h4><p>Productos, categorias, usuarios, ventas, stock, favoritos y carrito.</p></div></div></div>
        <div class="col-md-4"><div class="card h-100"><div class="card-body"><h4>Responsive</h4><p>Interfaz moderna con Bootstrap para computadora y celular.</p></div></div></div>
    </div>
</section>
<?php require_once "includes/footer.php"; ?>
