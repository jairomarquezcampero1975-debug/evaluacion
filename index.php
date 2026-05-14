<?php
include("config/conexion.php");
include("includes/header.php");
include("includes/navbar.php");

$productos = mysqli_query($conexion, "SELECT p.*, c.nombre_categoria 
FROM producto p 
INNER JOIN categoria c ON p.id_categoria = c.id_categoria 
WHERE p.estado = 'activo' 
LIMIT 6");

$categorias = mysqli_query($conexion, "SELECT * FROM categoria");
?>

<section class="banner">
    <div class="container text-center text-white">
        <h1 class="display-4 fw-bold">Venta de Celulares y Accesorios</h1>
        <p class="lead">Encuentra celulares, accesorios, audifonos, cargadores y smartwatch al mejor precio.</p>
        <a href="registro.php" class="btn btn-primary btn-lg">Comprar ahora</a>
    </div>
</section>

<section class="container my-5" id="productos">
    <h2 class="text-center mb-4">Productos destacados</h2>

    <div class="row">
        <?php while ($producto = mysqli_fetch_assoc($productos)) { ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow producto-card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $producto['nombre']; ?></h5>
                        <p class="card-text"><?php echo $producto['descripcion']; ?></p>
                        <p><strong>Marca:</strong> <?php echo $producto['marca']; ?></p>
                        <p><strong>Categoria:</strong> <?php echo $producto['nombre_categoria']; ?></p>
                        <h4 class="text-primary">Bs <?php echo $producto['precio']; ?></h4>
                        <a href="login.php" class="btn btn-dark w-100">Iniciar sesion para comprar</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<section class="bg-light py-5" id="categorias">
    <div class="container">
        <h2 class="text-center mb-4">Categorias</h2>

        <div class="row">
            <?php while ($categoria = mysqli_fetch_assoc($categorias)) { ?>
                <div class="col-md-3 mb-3">
                    <div class="card text-center shadow">
                        <div class="card-body">
                            <h5><?php echo $categoria['nombre_categoria']; ?></h5>
                            <p><?php echo $categoria['descripcion']; ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="container my-5">
    <h2 class="text-center mb-4">Beneficios de nuestra tienda</h2>

    <div class="row text-center">
        <div class="col-md-4">
            <div class="p-4 shadow rounded">
                <h4>Compra segura</h4>
                <p>Tu cuenta esta protegida con autenticacion de dos factores.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 shadow rounded">
                <h4>Productos variados</h4>
                <p>Tenemos celulares, accesorios, audio y tecnologia.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 shadow rounded">
                <h4>Gestion rapida</h4>
                <p>El sistema permite compras, favoritos y control de ventas.</p>
            </div>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>