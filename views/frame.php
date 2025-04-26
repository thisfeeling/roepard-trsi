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
    <!-- Bootstrap JS With Popper-->
    <script src="../dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- JS -->
    <script src="../js/main.js"></script>
    <script src="../js/chart.js"></script>
    <script src="../js/frame.js"></script>
</body>
</html>
 