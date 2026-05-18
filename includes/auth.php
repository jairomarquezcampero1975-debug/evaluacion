<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function rutaLogin() {
    return strpos($_SERVER['PHP_SELF'], '/admin/') !== false || strpos($_SERVER['PHP_SELF'], '/cliente/') !== false ? '../login.php' : 'login.php';
}

function protegerRuta() {
    if (!isset($_SESSION['id_usuario'])) {
        header('Location: ' . rutaLogin());
        exit();
    }
}

function soloAdmin() {
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        header('Location: ../cliente/inicio.php');
        exit();
    }
}

function soloCliente() {
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'cliente') {
        header('Location: ../admin/dashboard.php');
        exit();
    }
}

function limpiar($texto) {
    return trim($texto ?? '');
}

function e($texto) {
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

function imagenProducto($imagen, $prefijo = '') {
    if (empty($imagen)) {
        return $prefijo . 'assets/img/default-product.svg';
    }

    $ruta = $prefijo . $imagen;
    $rutaFisica = __DIR__ . '/../' . $imagen;

    if (file_exists($rutaFisica)) {
        return $ruta;
    }

    return $prefijo . 'assets/img/default-product.svg';
}
?>
