<?php
session_start();
include("config/conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $sql = "SELECT * FROM usuario WHERE correo = '$correo'";
    $resultado = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($resultado) == 1) {
        $usuario = mysqli_fetch_assoc($resultado);

        if (password_verify($contrasena, $usuario['contrasena'])) {
            $codigo = rand(100000, 999999);

            $_SESSION['temp_id_usuario'] = $usuario['id_usuario'];
            $_SESSION['temp_nombre'] = $usuario['nombre'];
            $_SESSION['temp_rol'] = $usuario['rol'];
            $_SESSION['codigo_2fa'] = $codigo;

            $id_usuario = $usuario['id_usuario'];
            mysqli_query($conexion, "UPDATE usuario SET codigo_2fa = '$codigo', estado_2fa = 0 WHERE id_usuario = $id_usuario");

            header("Location: verificar_2fa.php");
            exit();
        } else {
            $mensaje = "Contrasena incorrecta";
        }
    } else {
        $mensaje = "Usuario no encontrado";
    }
}

include("includes/header.php");
include("includes/navbar.php");
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h4>Inicio de sesion</h4>
                </div>

                <div class="card-body">
                    <?php if ($mensaje != "") { ?>
                        <div class="alert alert-danger"><?php echo $mensaje; ?></div>
                    <?php } ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Correo</label>
                            <input type="email" name="correo" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Contrasena</label>
                            <input type="password" name="contrasena" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-dark w-100">Ingresar</button>
                    </form>

                    <p class="mt-3 text-center">
                        No tienes cuenta? <a href="registro.php">Registrate</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>