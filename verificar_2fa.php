<?php
require_once "includes/auth.php";
require_once "config/conexion.php";

if (!isset($_SESSION['temp_id_usuario'], $_SESSION['codigo_2fa'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = limpiar($_POST['codigo'] ?? '');

    if (hash_equals((string)$_SESSION['codigo_2fa'], (string)$codigo)) {
        $_SESSION['id_usuario'] = $_SESSION['temp_id_usuario'];
        $_SESSION['nombre'] = $_SESSION['temp_nombre'];
        $_SESSION['rol'] = $_SESSION['temp_rol'];
        session_regenerate_id(true);

        $stmt = mysqli_prepare($conexion, "UPDATE usuario SET estado_2fa=1, codigo_2fa=NULL WHERE id_usuario=?");
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['id_usuario']);
        mysqli_stmt_execute($stmt);

        unset($_SESSION['temp_id_usuario'], $_SESSION['temp_nombre'], $_SESSION['temp_rol'], $_SESSION['temp_correo'], $_SESSION['codigo_2fa'], $_SESSION['correo_2fa_enviado']);

        if ($_SESSION['rol'] === 'admin') { header("Location: admin/dashboard.php"); exit(); }
        header("Location: cliente/inicio.php");
        exit();
    }
    $mensaje = "Codigo incorrecto";
}

require_once "includes/header.php";
require_once "includes/navbar.php";
?>
<div class="container">
    <div class="auth-box">
        <div class="card auth-card">
            <div class="card-header text-white text-center">
                <span class="hero-badge mb-3"><i class="bi bi-envelope-check"></i> Segundo factor</span>
                <h3 class="fw-black mb-0">Verificacion 2FA</h3>
                <p class="mb-0 text-white-50">Revisa el correo temporal y escribe el codigo.</p>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info text-center">Se envio un codigo de verificacion a tu correo.</div>
                <?php if ($mensaje) { ?><div class="alert alert-danger"><?php echo e($mensaje); ?></div><?php } ?>
                <form method="POST">
                    <div class="mb-3"><label>Codigo 2FA</label><input type="text" name="codigo" class="form-control text-center fs-2 fw-bold" maxlength="6" placeholder="000000" required></div>
                    <button class="btn btn-primary w-100"><i class="bi bi-check2-circle me-2"></i> Verificar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once "includes/footer.php"; ?>
