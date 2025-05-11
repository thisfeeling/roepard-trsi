<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>frame</title>
    <!-- Bootstrap CSS -->
    <link href="/trsi/frontend/dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="/trsi/frontend/dist/fontawesome/css/fontawesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="/trsi/frontend/css/style.css">
</head>

<body>

    <!-- Navbar -->
    <?php include __DIR__ . '/../../frontend/components/navbar.php'; ?>

    <p id="horaColombia"></p>

    <div class="container-todo">
        <div class="title-container">

            <h2>Registro de Usuarios</h2>
        </div>
        <div class="chart-container">
            <canvas id="usuariosChart" class="usuariosChart"></canvas>
        </div>
    </div>

    <!-- jQuery -->
    <script src="/trsi/frontend/dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS With Popper-->
    <script src="/trsi/frontend/dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- Chart JS -->
    <script src="/trsi/frontend/dist/chart/js/chart.umd.min.js"></script>
    <!-- FontAwesome JS -->
    <script src="/trsi/frontend/dist/fontawesome/js/all.min.js"></script>
    <!-- MomentJS -->
    <script src="/trsi/frontend/dist/moment/js/moment.js"></script>
    <script src="/trsi/frontend/dist/moment/js/moment-timezone-with-data.js"></script>
    <!-- JS -->
    <script src="/trsi/frontend/js/main.js"></script>
    <script src="/trsi/frontend/js/frame.js"></script>
</body>

</html>