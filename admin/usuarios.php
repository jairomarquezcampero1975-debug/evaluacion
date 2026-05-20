<?php
require_once "../includes/auth.php";
protegerRuta();
soloAdmin();
require_once "../config/conexion.php";

$buscar = limpiar($_GET['buscar'] ?? '');

$sql = "
SELECT id_usuario, nombre, correo, rol, estado, estado_2fa, fecha_registro
FROM usuario
";

if ($buscar != '') {
    $sql .= "
    WHERE (
        id_usuario LIKE ?
        OR nombre LIKE ?
        OR correo LIKE ?
        OR rol LIKE ?
        OR estado LIKE ?
    )
    ";
}

$sql .= " ORDER BY id_usuario DESC";

$stmt = mysqli_prepare($conexion, $sql);

if ($buscar != '') {
    $filtro = "%$buscar%";
    mysqli_stmt_bind_param(
        $stmt,
        "sssss",
        $filtro,
        $filtro,
        $filtro,
        $filtro,
        $filtro
    );
    mysqli_stmt_execute($stmt);
    $usuarios = mysqli_stmt_get_result($stmt);
} else {
    $usuarios = mysqli_query($conexion, $sql);
}

$total_admin = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) total FROM usuario WHERE rol='admin'")
)['total'];

$total_cliente = mysqli_fetch_assoc(
    mysqli_query($conexion, "SELECT COUNT(*) total FROM usuario WHERE rol='cliente'")
)['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Usuarios</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/estilos.css">
</head>

<body class="admin-body">

<nav class="navbar navbar-dark admin-navbar sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand brand-logo" href="dashboard.php">
            PapuStore Admin
        </a>

        <div class="d-flex gap-2">
            <a href="../logout.php" class="btn btn-danger btn-sm">
                <i class="bi bi-box-arrow-right"></i> Salir
            </a>
        </div>
    </div>
</nav>

<div class="admin-shell">

<aside class="admin-sidebar">
    <div class="menu-title">Panel</div>
    <a class="admin-link" href="dashboard.php">Dashboard</a>
    <a class="admin-link active" href="usuarios.php">Usuarios</a>
</aside>

<main class="admin-content">

<section class="page-hero">
    <h1>Usuarios registrados</h1>
</section>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="metric-card metric-dark">
            <span>Administradores</span>
            <h2><?php echo $total_admin; ?></h2>
        </div>
    </div>

    <div class="col-md-6">
        <div class="metric-card metric-purple">
            <span>Clientes</span>
            <h2><?php echo $total_cliente; ?></h2>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="input-group">
                <input
                    type="text"
                    name="buscar"
                    class="form-control"
                    placeholder="Buscar por ID, nombre, correo, rol o estado..."
                    value="<?php echo e($buscar); ?>"
                >

                <button class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>

                <?php if($buscar != ''){ ?>
                    <a href="usuarios.php" class="btn btn-secondary">
                        Limpiar
                    </a>
                <?php } ?>
            </div>
        </form>
    </div>
</div>

<div class="card">
<div class="card-body">

<h4>Listado de usuarios</h4>

<div class="table-responsive">
<table class="table table-hover align-middle">

<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Correo</th>
    <th>Rol</th>
    <th>Estado</th>
    <th>2FA</th>
    <th>Acciones</th>
</tr>

<?php $hay = false; ?>
<?php while($u=mysqli_fetch_assoc($usuarios)){ $hay = true; ?>

<tr>
    <td>#<?php echo $u['id_usuario']; ?></td>

    <td><?php echo e($u['nombre']); ?></td>

    <td><?php echo e($u['correo']); ?></td>

    <td>
        <span class="badge bg-<?php echo $u['rol']=='admin'?'dark':'primary'; ?>">
            <?php echo $u['rol']; ?>
        </span>
    </td>

    <td>
        <span class="badge bg-<?php echo $u['estado']=='activo'?'success':'danger'; ?>">
            <?php echo $u['estado']; ?>
        </span>
    </td>

    <td>
        <span class="badge bg-<?php echo $u['estado_2fa']?'success':'warning'; ?>">
            <?php echo $u['estado_2fa'] ? 'Verificado' : 'Pendiente'; ?>
        </span>
    </td>

    <td>
        <a href="editar_usuario.php?id=<?php echo $u['id_usuario']; ?>"
           class="btn btn-warning btn-sm">
           <i class="bi bi-pencil"></i>
        </a>

        <?php if($u['estado']=="activo"){ ?>

            <a href="cambiar_estado_usuario.php?id=<?php echo $u['id_usuario']; ?>&accion=banear"
               class="btn btn-danger btn-sm"
               onclick="return confirm('¿Banear usuario?')">
               <i class="bi bi-slash-circle"></i>
            </a>

        <?php } else { ?>

            <a href="cambiar_estado_usuario.php?id=<?php echo $u['id_usuario']; ?>&accion=activar"
               class="btn btn-success btn-sm">
               <i class="bi bi-check-circle"></i>
            </a>

        <?php } ?>
    </td>
</tr>

<?php } ?>

<?php if(!$hay){ ?>
<tr>
    <td colspan="7" class="text-center text-muted py-4">
        No se encontraron usuarios.
    </td>
</tr>
<?php } ?>

</table>
</div>
</div>
</div>

</main>
</div>
</body>
</html>