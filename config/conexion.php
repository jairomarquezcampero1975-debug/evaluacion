<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "sistema_ventas";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conexion) {
    die("Error de conexion a la base de datos");
}

mysqli_set_charset($conexion, "utf8mb4");
?>
