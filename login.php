<?php
require_once "includes/auth.php";
require_once "config/conexion.php";
require_once "includes/enviar_correo.php";

$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = strtolower(limpiar($_POST['correo'] ?? ''));
    $contrasena = $_POST['contrasena'] ?? '';

    $stmt = mysqli_prepare($conexion, "SELECT * FROM usuario WHERE correo=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($usuario = mysqli_fetch_assoc($resultado)) {
        if (password_verify($contrasena, $usuario['contrasena'])) {
            session_regenerate_id(true);
            $codigo = (string)random_int(100000, 999999);
            $_SESSION['temp_id_usuario'] = $usuario['id_usuario'];
            $_SESSION['temp_nombre'] = $usuario['nombre'];
            $_SESSION['temp_rol'] = $usuario['rol'];
            $_SESSION['temp_correo'] = $usuario['correo'];
            $_SESSION['codigo_2fa'] = $codigo;
            $_SESSION['correo_2fa_enviado'] = enviarCodigoCorreo($usuario['correo'], $codigo) ? 1 : 0;

            $stmt2 = mysqli_prepare($conexion, "UPDATE usuario SET codigo_2fa=?, estado_2fa=0 WHERE id_usuario=?");
            mysqli_stmt_bind_param($stmt2, "si", $codigo, $usuario['id_usuario']);
            mysqli_stmt_execute($stmt2);
            header("Location: verificar_2fa.php");
            exit();
        }
    }
    $mensaje = "Correo o contrasena incorrectos";
}
require_once "includes/header.php";
require_once "includes/navbar.php";
?>
<div class="container">
    <div class="auth-box">
        <div class="card auth-card">
            <div class="card-header text-white text-center">
                <span class="hero-badge mb-3"><i class="bi bi-shield-lock"></i> Acceso seguro</span>
                <h3 class="fw-black mb-0">Iniciar sesion</h3>
                <p class="mb-0 text-white-50">Ingresa y confirma tu codigo 2FA.</p>
            </div>
            <div class="card-body p-4">
                <?php if ($mensaje) { ?><div class="alert alert-danger"><?php echo e($mensaje); ?></div><?php } ?>
                <form method="POST">
                    <div class="mb-3"><label>Correo</label><input type="email" name="correo" class="form-control"  required></div>
                    <div class="mb-3"><label>Contrasena</label><input type="password" name="contrasena" class="form-control" placeholder="Tu contrasena" required></div>
                    <button class="btn btn-dark w-100"><i class="bi bi-box-arrow-in-right me-2"></i> Ingresar</button>
                </form>
                <p class="mt-4 text-center mb-0">No tienes cuenta? <a href="registro.php" class="fw-bold">Registrate aqui</a></p>
            </div>
        </div>
    </div>
</div>
<?php require_once "includes/footer.php"; ?>
