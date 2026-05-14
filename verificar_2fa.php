<?php
session_start();
include("config/conexion.php");

if (!isset($_SESSION['temp_id_usuario'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";
$codigo_mostrado = $_SESSION['codigo_2fa'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_ingresado = $_POST['codigo'];

    if ($codigo_ingresado == $_SESSION['codigo_2fa']) {
        $_SESSION['id_usuario'] = $_SESSION['temp_id_usuario'];
        $_SESSION['nombre'] = $_SESSION['temp_nombre'];
        $_SESSION['rol'] = $_SESSION['temp_rol'];

        $id_usuario = $_SESSION['id_usuario'];
        mysqli_query($conexion, "UPDATE usuario SET estado_2fa = 1 WHERE id_usuario = $id_usuario");

        unset($_SESSION['temp_id_usuario']);
        unset($_SESSION['temp_nombre']);
        unset($_SESSION['temp_rol']);
        unset($_SESSION['codigo_2fa']);

        if ($_SESSION['rol'] == 'admin') {
            header("Location: admin/dashboard.php");
            exit();
        } else {
            header("Location: cliente/inicio.php");
            exit();
        }
    } else {
        $mensaje = "Codigo incorrecto";
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
                    <h4>Verificacion 2FA</h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-warning text-center">
                        Codigo temporal simulado: <strong><?php echo $codigo_mostrado; ?></strong>
                    </div>

                    <?php if ($mensaje != "") { ?>
                        <div class="alert alert-danger"><?php echo $mensaje; ?></div>
                    <?php } ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Ingrese el codigo 2FA</label>
                            <input type="text" name="codigo" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Verificar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>