<?php
require_once "config/conexion.php";
require_once "includes/auth.php";
require_once "includes/header.php";
require_once "includes/navbar.php";

$productos = mysqli_query($conexion, "SELECT p.*, c.nombre_categoria FROM producto p INNER JOIN categoria c ON p.id_categoria=c.id_categoria WHERE p.estado='activo' ORDER BY p.id_producto DESC LIMIT 6");
$categorias = mysqli_query($conexion, "SELECT * FROM categoria ORDER BY nombre_categoria ASC");
$total_productos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) total FROM producto WHERE estado='activo'"))['total'];
$total_categorias = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) total FROM categoria"))['total'];
?>
<section class="banner">
    <div class="container hero-content">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="hero-badge"><i class="bi bi-stars"></i> Tienda digital moderna y segura</span>
                <h1 class="hero-title">Tecnologia que se ve <span>premium</span></h1>
                <p class="hero-text">Compra celulares, accesorios, audio, gaming y smartwatch con una experiencia rapida, responsive y protegida con 2FA.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="registro.php" class="btn btn-primary btn-lg"><i class="bi bi-bag-check me-2"></i> Comprar ahora</a>
                    <a href="#productos" class="btn btn-outline-light btn-lg"><i class="bi bi-grid me-2"></i> Ver catalogo</a>
                </div>
                <div class="row g-3 mt-4">
                    <div class="col-4"><div class="hero-badge w-100 justify-content-center mb-0"><strong><?php echo $total_productos; ?>+</strong> productos</div></div>
                    <div class="col-4"><div class="hero-badge w-100 justify-content-center mb-0"><strong><?php echo $total_categorias; ?></strong> categorias</div></div>
                    <div class="col-4"><div class="hero-badge w-100 justify-content-center mb-0"><strong>2FA</strong> seguro</div></div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-panel">
                    <div class="hero-phone"></div>
                    <div class="hero-floating">
                        <div class="d-flex align-items-center gap-3">
                            <div class="feature-icon mb-0"><i class="bi bi-lightning-charge-fill"></i></div>
                            <div>
                                <h5 class="mb-1"></h5>
                                <p class="mb-0">Productos con stock, favoritos, carrito y venta registrada.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-pad" id="productos">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4 reveal">
            <div>
                <h2 class="section-title">Productos destacados</h2>
                <p class="section-subtitle mb-0">Un catalogo limpio, moderno y preparado para convertir visitas en compras.</p>
            </div>
            <a href="login.php" class="btn btn-dark"><i class="bi bi-lock me-2"></i> Entrar para comprar</a>
        </div>
        <div class="row g-4">
            <?php while ($producto = mysqli_fetch_assoc($productos)) { ?>
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="product-card">
                        <img class="product-img" src="<?php echo e(imagenProducto($producto['imagen'])); ?>" alt="<?php echo e($producto['nombre']); ?>">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                <span class="badge badge-soft"><?php echo e($producto['nombre_categoria']); ?></span>
                                <span class="badge bg-dark">Stock <?php echo (int)$producto['stock']; ?></span>
                            </div>
                            <h5 class="card-title"><?php echo e($producto['nombre']); ?></h5>
                            <p class="card-text flex-grow-1"><?php echo e($producto['descripcion']); ?></p>
                            <p class="mb-2"><i class="bi bi-award me-1"></i> <strong>Marca:</strong> <?php echo e($producto['marca']); ?></p>
                            <div class="price mb-3">Bs <?php echo number_format($producto['precio'], 2); ?></div>
                            <a href="login.php" class="btn btn-primary w-100"><i class="bi bi-cart-plus me-2"></i> Comprar</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="section-pad bg-white" id="categorias">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <h2 class="section-title text-center">Categorias principales</h2>
            <p class="section-subtitle mx-auto mb-0">El sistema organiza productos por categorias para facilitar busqueda, filtros y administracion.</p>
        </div>
        <div class="row g-4">
            <?php while ($categoria = mysqli_fetch_assoc($categorias)) { ?>
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="category-pill h-100">
                        <div class="feature-icon"><i class="bi bi-box-seam"></i></div>
                        <h5 class="fw-bold"><?php echo e($categoria['nombre_categoria']); ?></h5>
                        <p class="mb-0 text-muted"><?php echo e($categoria['descripcion']); ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="section-pad" id="beneficios">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 reveal">
                <span class="mini-badge text-dark bg-white"><i class="bi bi-shield-lock"></i> </span>
                <h2 class="section-title">Mas que una tienda basica</h2>
                <p class="section-subtitle"></p>
            </div>
            <div class="col-lg-7">
                <div class="row g-4">
                    <div class="col-md-6 reveal"><div class="feature-card"><div class="feature-icon"><i class="bi bi-shield-check"></i></div><h4>Compra segura</h4><p>Login con contraseña y  codigo 2FA por correo.</p></div></div>
                    <div class="col-md-6 reveal"><div class="feature-card"><div class="feature-icon"><i class="bi bi-speedometer2"></i></div><h4>Panel admin</h4><p>Productos, categorias, usuarios, ventas, estados y control de stock.</p></div></div>
                    <div class="col-md-6 reveal"><div class="feature-card"><div class="feature-icon"><i class="bi bi-cart-check"></i></div><h4>Carrito real</h4><p>Agrega, elimina, modifica cantidades, calcula subtotal y registra compras.</p></div></div>
                    <div class="col-md-6 reveal"><div class="feature-card"><div class="feature-icon"><i class="bi bi-phone"></i></div><h4>Responsive</h4><p>Interfaz moderna  y formularios adaptados a celular.</p></div></div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once "includes/footer.php"; ?>
