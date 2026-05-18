<?php
require_once "includes/auth.php";
require_once "config/conexion.php";

$mensaje = "";
$tipo = "info";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = limpiar($_POST['nombre']);
    $correo = limpiar($_POST['correo']);
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $rol = 'cliente';
    $estado = 0;

    $stmt = mysqli_prepare($conexion, "INSERT INTO usuario(nombre, correo, contrasena, rol, estado_2fa) VALUES(?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $correo, $contrasena, $rol, $estado);
    if (mysqli_stmt_execute($stmt)) {
        $mensaje = "Usuario registrado correctamente. Ahora puedes iniciar sesion.";
        $tipo = "success";
    } else {
        $mensaje = "No se pudo registrar. Puede que el correo ya exista.";
        $tipo = "danger";
    }
}
require_once "includes/header.php";
require_once "includes/navbar.php";
?>
<div class="container">
    <div class="auth-box">
        <div class="card">
            <div class="card-header text-white text-center"><h4>Registro de usuario</h4></div>
            <div class="card-body">
                <?php if ($mensaje) { ?><div class="alert alert-<?php echo $tipo; ?>"><?php echo e($mensaje); ?></div><?php } ?>
                <form method="POST">
                    <div class="mb-3"><label>Nombre</label><input type="text" name="nombre" class="form-control" required></div>
                    <div class="mb-3"><label>Correo</label><input type="email" name="correo" class="form-control" required></div>
                    <div class="mb-3"><label>Contrasena</label><input type="password" name="contrasena" class="form-control" required></div>
                    <button class="btn btn-primary w-100">Registrarse</button>
                </form>
                <p class="mt-3 text-center">Ya tienes cuenta? <a href="login.php">Iniciar sesion</a></p>
            </div>
        </div>
    </div>
</div>
<?php require_once "includes/footer.php"; ?>
