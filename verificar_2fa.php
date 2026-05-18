<?php
require_once "includes/auth.php";
require_once "config/conexion.php";

if (!isset($_SESSION['temp_id_usuario'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";
$codigo_mostrado = $_SESSION['codigo_2fa'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = limpiar($_POST['codigo']);
    if (hash_equals($_SESSION['codigo_2fa'], $codigo)) {
        $_SESSION['id_usuario'] = $_SESSION['temp_id_usuario'];
        $_SESSION['nombre'] = $_SESSION['temp_nombre'];
        $_SESSION['rol'] = $_SESSION['temp_rol'];
        session_regenerate_id(true);

        $stmt = mysqli_prepare($conexion, "UPDATE usuario SET estado_2fa=1, codigo_2fa=NULL WHERE id_usuario=?");
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['id_usuario']);
        mysqli_stmt_execute($stmt);

        unset($_SESSION['temp_id_usuario'], $_SESSION['temp_nombre'], $_SESSION['temp_rol'], $_SESSION['codigo_2fa']);
        header($_SESSION['rol'] === 'admin' ? "Location: admin/dashboard.php" : "Location: cliente/inicio.php");
        exit();
    }
    $mensaje = "Codigo incorrecto";
}
require_once "includes/header.php";
require_once "includes/navbar.php";
?>
<div class="container">
    <div class="auth-box">
        <div class="card">
            <div class="card-header text-white text-center"><h4>Verificacion 2FA</h4></div>
            <div class="card-body">
                <div class="alert alert-warning text-center">Codigo temporal simulado: <strong><?php echo e($codigo_mostrado); ?></strong></div>
                <?php if ($mensaje) { ?><div class="alert alert-danger"><?php echo e($mensaje); ?></div><?php } ?>
                <form method="POST">
                    <div class="mb-3"><label>Ingrese el codigo 2FA</label><input type="text" name="codigo" class="form-control" required></div>
                    <button class="btn btn-primary w-100">Verificar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once "includes/footer.php"; ?>
