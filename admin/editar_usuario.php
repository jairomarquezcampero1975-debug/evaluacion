<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";

$id = intval($_GET['id']);

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];

    mysqli_query($conexion,"
    UPDATE usuario
    SET nombre='$nombre',
        correo='$correo'
    WHERE id_usuario=$id
    ");

    header("Location: usuarios.php");
    exit;
}

$usuario = mysqli_fetch_assoc(
    mysqli_query($conexion,"SELECT * FROM usuario WHERE id_usuario=$id")
);
?>

<h2>Editar Usuario</h2>

<form method="POST">

Nombre:
<input type="text" name="nombre"
value="<?php echo e($usuario['nombre']); ?>">

<br><br>

Correo:
<input type="email" name="correo"
value="<?php echo e($usuario['correo']); ?>">

<br><br>

<button type="submit">
Guardar cambios
</button>

</form>