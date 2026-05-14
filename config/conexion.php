<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "sistema_ventas";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conexion) {
    die("Error de conexion: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");
?>