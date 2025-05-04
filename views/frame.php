<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>frame</title>
    <!-- Bootstrap CSS -->
    <link href="../dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <!-- Incluir el navbar -->
    <?php
    include './navbar.php';
    ?>

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
    <script src="../dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS With Popper-->
    <script src="../dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- ChartJS -->
    <script src="../dist/chart/js/chart.umd.min.js"></script>
    <!-- MomentJS -->
    <script src="../dist/moment/js/moment.js"></script>
    <script src="../dist/moment/js/moment-timezone-with-data.js"></script>
    <!-- JS -->
    <script src="../js/main.js"></script>
    <script src="../js/frame.js"></script>
</body>

</html>