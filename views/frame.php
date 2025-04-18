<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>frame</title>
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
    <!-- Bootstrap Bundle with Popper -->
    <script src="../js/bootstrap.bundle.min.js"></script>
    <!-- main JS -->
    <script src="../js/main.js"></script>
    <script src="../js/chart.js"></script>
    <script src="../js/frame.js"></script>
</body>
</html>
