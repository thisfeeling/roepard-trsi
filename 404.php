<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <!-- Bootstrap CSS -->
    <link href="/trsi/frontend/dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="/trsi/frontend/dist/fontawesome/css/fontawesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="/trsi/frontend/css/style.css">
    <link rel="stylesheet" href="/trsi/frontend/css/variables.css">
</head>

<body theme-404>
    <!-- Navbar -->
    <?php include './frontend/components/navbar.php'; ?>

    <section class="d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">
        <h1 style="font-size: 3rem; font-weight: bold; color: var(--uam-blue);">404 - Página no encontrada</h1>
        <p class="mt-3" style="font-size: 1.2rem; color: var(--uam-gray); max-width: 600px; text-align: center;">
            Lo sentimos, pero la página que solicitaste no existe.<br>
            Esto puede deberse a que:
        </p>
        <ul style="color: var(--uam-black); font-size: 1.1rem; max-width: 500px;">
            <li>La página fue movida, está desactualizada o aún no ha sido creada.</li>
            <li>Escribiste mal la dirección.</li>
            <li>Seguiste un enlace que ya no es válido.</li>
        </ul>
        <a href="/trsi/index.php" class="btn btn-uam mt-4">Volver al inicio</a>
    </section>
</body>

</html>