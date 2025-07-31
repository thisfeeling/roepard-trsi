<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Commits</title>

    <!-- Bootstrap 5 CSS -->
    <link href="../dist/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- DataTables Bootstrap 5 CSS -->
    <link href="../dist/datatables/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="../dist/fontawesome/css/fontawesome.min.css" />

    <!-- Variables y estilos -->
    <link rel="stylesheet" href="../css/variables.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <!-- Icono -->
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
</head>

<body class="bg-white text-light">

    <!-- Navbar -->
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <div class="uam-bar-commits my-5 mx-auto p-4">
        <h2 class="text-center mb-4" style="font-size: 2rem; font-weight: bold; color: var(--uam-yellow);">Registros de
            cambios</h2>
        <div class="table-responsive">
            <table id="tablaCommits" class="table table-striped table-bordered table-hover text-white">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mensaje</th>
                        <th>Autor</th>
                        <th>Fecha</th>
                        <th>Ver</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- Regresar dentro del panel -->
        <div class="d-flex justify-content-start mt-4">
            <a href="./services.php" class="btn btn-uam d-flex align-items-center justify-content-center"
                style="font-size: 1.4rem; font-weight: bold; border-radius: 15px; width: 150px; height: 50px; padding: 0;">
                Regresar
            </a>
        </div>
    </div>

    <!-- jQuery -->
    <script src="../dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS Bundle -->
    <script src="../dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- Chart.js -->
    <script src="../dist/chart/js/chart.umd.min.js"></script>
    <!-- FontAwesome -->
    <script src="../dist/fontawesome/js/all.min.js"></script>
    <!-- DataTables JS -->
    <script src="../dist/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../dist/datatables/js/dataTables.bootstrap5.min.js"></script>
    <!-- JS personalizado -->
    <script src="../js/commits.js"></script>

</body>

</html>