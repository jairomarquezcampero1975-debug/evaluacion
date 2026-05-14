<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function protegerRuta() {
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: ../login.php");
        exit();
    }
}

function soloAdmin() {
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
        header("Location: ../cliente/inicio.php");
        exit();
    }
}

function soloCliente() {
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'cliente') {
        header("Location: ../admin/dashboard.php");
        exit();
    }
}
?>