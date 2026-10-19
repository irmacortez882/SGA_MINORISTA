<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema SGA Minorista</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Estilos personalizados usando URL absoluta -->
    <link rel="stylesheet" href="<?php echo URL; ?>views/assets/css/estilos.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <?php if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok"): ?>

        <!-- ENCABEZADO Y MENÚ DE NAVEGACIÓN -->
        <?php include "views/includes/header.php"; ?>

        <!-- CONTENIDO DINÁMICO DEL MÓDULO SELECCIONADO -->
        <main class="flex-grow-1 py-4">
            <?php Enrutador::cargarPagina(); ?>
        </main>

        <!-- PIE DE PÁGINA -->
        <?php include "views/includes/footer.php"; ?>

        <!-- SCRIPTS JS (Usando CDN para jQuery por seguridad y constante URL para locales) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo URL; ?>views/assets/js/main.js"></script>

    <?php else: ?>

        <!-- FORMULARIO DE LOGIN SI NO HAY SESIÓN ACTIVA -->
        <main class="flex-grow-1 d-flex align-items-center justify-content-center">
            <?php include "views/modules/login.php"; ?>
        </main>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php endif; ?>

</body>
</html>