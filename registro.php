<?php
require_once "includes/auth.php";
require_once "config/conexion.php";
require_once "includes/enviar_correo.php";

$mensaje = "";
$tipo = "info";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = limpiar($_POST['nombre'] ?? '');
    $correo = strtolower(limpiar($_POST['correo'] ?? ''));
    $contrasena_plana = $_POST['contrasena'] ?? '';
    $repetir_contrasena = $_POST['repetir_contrasena'] ?? '';

    if ($nombre === '' || $correo === '' || $contrasena_plana === '' || $repetir_contrasena === '') {
        $mensaje = "Completa todos los campos.";
        $tipo = "danger";

    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Ingresa un correo valido.";
        $tipo = "danger";

    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/', $contrasena_plana)) {
        $mensaje = "La contrasena debe tener minimo 8 caracteres, una mayuscula y un numero.";
        $tipo = "danger";

    } elseif ($contrasena_plana !== $repetir_contrasena) {
        $mensaje = "Las contrasenas no coinciden.";
        $tipo = "danger";

    } else {
        $stmt = mysqli_prepare($conexion, "SELECT id_usuario FROM usuario WHERE correo=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $correo);
        mysqli_stmt_execute($stmt);
        $existe = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if ($existe) {
            $mensaje = "Ese correo ya esta registrado.";
            $tipo = "danger";
        } else {
            $contrasena = password_hash($contrasena_plana, PASSWORD_DEFAULT);
            $rol = 'cliente';
            $codigo = (string)random_int(100000, 999999);
            $estado = 0;

            $stmt = mysqli_prepare($conexion, "
                INSERT INTO usuario
                (nombre, correo, contrasena, rol, codigo_2fa, estado_2fa)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            mysqli_stmt_bind_param(
                $stmt,
                "sssssi",
                $nombre,
                $correo,
                $contrasena,
                $rol,
                $codigo,
                $estado
            );

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['temp_id_usuario'] = mysqli_insert_id($conexion);
                $_SESSION['temp_nombre'] = $nombre;
                $_SESSION['temp_rol'] = $rol;
                $_SESSION['temp_correo'] = $correo;
                $_SESSION['codigo_2fa'] = $codigo;
                $_SESSION['correo_2fa_enviado'] =
                    enviarCodigoCorreo($correo, $codigo) ? 1 : 0;

                header("Location: verificar_2fa.php");
                exit();
            } else {
                $mensaje = "No se pudo registrar el usuario.";
                $tipo = "danger";
            }
        }
    }
}

require_once "includes/header.php";
require_once "includes/navbar.php";
?>

<div class="container">
    <div class="auth-box">
        <div class="card auth-card">

            <div class="card-header text-white text-center">
                <span class="hero-badge mb-3">
                    <i class="bi bi-person-plus"></i> Nueva cuenta
                </span>

                <h3 class="fw-black mb-0">
                    Crear usuario
                </h3>

                <p class="mb-0 text-white-50">
                    Registro con contrasena segura y verificacion 2FA.
                </p>
            </div>

            <div class="card-body p-4">

                <?php if ($mensaje) { ?>
                    <div class="alert alert-<?php echo $tipo; ?>">
                        <?php echo e($mensaje); ?>
                    </div>
                <?php } ?>

                <form method="POST">

                    <div class="mb-3">
                        <label>Nombre</label>
                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            placeholder="Tu nombre completo"
                            value="<?php echo e($nombre ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label>Correo</label>
                        <input
                            type="email"
                            name="correo"
                            class="form-control"
                            placeholder="correo@gmail.com"
                            value="<?php echo e($correo ?? ''); ?>"
                            required
                        >
                        
                    </div>

                    <div class="mb-3">
                        <label>Contrasena</label>
                        <input
                            type="password"
                            name="contrasena"
                            class="form-control"
                            placeholder="Minimo 8, mayuscula y numero"
                            required
                            minlength="8"
                            pattern="(?=.*[A-Z])(?=.*[0-9]).{8,}"
                            title="La contrasena debe tener minimo 8 caracteres, una mayuscula y un numero"
                        >
                    </div>

                    <div class="mb-3">
                        <label>Repetir contrasena</label>
                        <input
                            type="password"
                            name="repetir_contrasena"
                            class="form-control"
                            placeholder="Repite tu contrasena"
                            required
                        >
                    </div>

                    <button class="btn btn-primary w-100">
                        <i class="bi bi-shield-check me-2"></i>
                        Registrarse
                    </button>

                </form>

                <p class="mt-4 text-center mb-0">
                    Ya tienes cuenta?
                    <a href="login.php" class="fw-bold">
                        Iniciar sesion
                    </a>
                </p>

            </div>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>