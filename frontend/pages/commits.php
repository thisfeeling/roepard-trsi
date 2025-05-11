<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Commits</title>

    <!-- Bootstrap 5 CSS -->
    <link href="/trsi/frontend/dist/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- DataTables Bootstrap 5 CSS -->
    <link href="/trsi/frontend/dist/datatables/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="/trsi/frontend/dist/fontawesome/css/fontawesome.min.css" />

    <!-- Variables y estilos -->
    <link rel="stylesheet" href="/trsi/frontend/css/variables.css" />
    <link rel="stylesheet" href="/trsi/frontend/css/style.css" />
</head>

<body class="bg-dark text-light">

    <!-- Navbar -->
    <?php include __DIR__ . '/../../frontend/components/navbar.php'; ?>

    <div class="container my-5">
        <h2 class="text-center text-white mb-4">Commits recientes</h2>
        <div class="table-responsive">
            <table id="tablaCommits" class="table table-striped table-bordered table-hover text-white">
                <thead>
                    <tr>
                        <th>Mensaje</th>
                        <th>Autor</th>
                        <th>Fecha</th>
                        <th>Ver</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- jQuery -->
    <script src="/trsi/frontend/dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS Bundle -->
    <script src="/trsi/frontend/dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- Chart.js -->
    <script src="/trsi/frontend/dist/chart/js/chart.umd.min.js"></script>
    <!-- FontAwesome -->
    <script src="/trsi/frontend/dist/fontawesome/js/all.min.js"></script>
    <!-- DataTables JS -->
    <script src="/trsi/frontend/dist/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/trsi/frontend/dist/datatables/js/dataTables.bootstrap5.min.js"></script>
    <!-- JS personalizado -->
    <script src="/trsi/frontend/js/commits.js"></script>

</body>

</html>