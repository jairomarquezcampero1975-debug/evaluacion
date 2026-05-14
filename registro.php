<?php
session_start();
include("config/conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $contrasena_segura = password_hash($contrasena, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuario (nombre, correo, contrasena, rol, estado_2fa) 
            VALUES ('$nombre', '$correo', '$contrasena_segura', 'cliente', 0)";

    if (mysqli_query($conexion, $sql)) {
        $mensaje = "Usuario registrado correctamente";
    } else {
        $mensaje = "Error al registrar usuario";
    }
}

include("includes/header.php");
include("includes/navbar.php");
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Registro de usuario</h4>
                </div>

                <div class="card-body">
                    <?php if ($mensaje != "") { ?>
                        <div class="alert alert-info"><?php echo $mensaje; ?></div>
                    <?php } ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Correo</label>
                            <input type="email" name="correo" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Contrasena</label>
                            <input type="password" name="contrasena" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                    </form>

                    <p class="mt-3 text-center">
                        Ya tienes cuenta? <a href="login.php">Iniciar sesion</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>