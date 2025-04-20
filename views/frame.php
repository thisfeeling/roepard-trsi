<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>frame</title>
    <!-- Bootstrap CSS -->
    <link href="../dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- Icon of the page -->
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
</head>
<body>

    <!-- Incluir el navbar -->
    <?php
    include './navbar.php';
    ?>
    <div class="container-todo">
        <div class="title-container">
            <h2>Registro de Usuarios</h2>
        </div>
        <div class="chart-container">
            <canvas id="usuariosChart" class="usuariosChart"></canvas>
        </div>
    </div>

    <!-- Incluir el footer -->
    <?php
    include './footer.php';
    ?>

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
    <!-- Bootstrap JS -->
    <script src="../dist/bootstrap/js/bootstrap.js"></script>
    <!-- JS -->
    <script src="../js/main.js"></script>
    <script src="../js/chart.js"></script>
    <script src="../js/frame.js"></script>
</body>
</html>
