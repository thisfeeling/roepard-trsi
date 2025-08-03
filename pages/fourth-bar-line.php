<?php
// Incluir el archivo de conexión a la base de datos
require_once __DIR__ . '/../core/DBConfig.php';
// Crear una instancia de la clase de conexión
$auth = new DBConfig();
$db = $auth->getConnection();
// Verifica si la sesión está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verifica si el usuario está autenticado
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Recupera el ID del usuario desde la sesión
    $user_id = $_SESSION['user_id']; // Asumiendo que 'user_id' está guardado en la sesión
    // Consulta para obtener los datos del usuario
    $stmt = $db->prepare("SELECT first_name, last_name, email, role_id, status_id FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    // Si el usuario existe, obtenemos sus datos
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
        $email = $user['email'];
        $role_id = $user['role_id'];
        $status_id = $user['status_id'];
        // se queda en services.php
    } else {
        // Si no se encuentra el usuario en la base de datos
        header("Location: ../index.html");
        exit();
    }
} else {
    // El usuario no está autenticado, redirige a la página de inicio de sesión
    header("Location: ../index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fourth-bar-line</title>
    <!-- Bootstrap 5 CSS -->
    <link href="../dist/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <!-- DataTables Bootstrap 5 CSS -->
    <link href="../dist/datatables/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="../dist/fontawesome/css/fontawesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- Icono -->
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
</head>

<body style="background-color: var(--uam-white) !important;">

    <!-- Navbar -->
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <!-- Modal para mensajes -->
    <div class="modal fade" id="modalMessage" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalMessageContent"></div>
            </div>
        </div>
    </div>

    <main class="container-fluid d-flex flex-column align-items-center justify-content-center"
        style="min-height: 80vh;">
        <div class="container-todo p-5"
            style="background: var(--uam-blue); border-radius: 24px; width: 90%; max-width: 1100px; margin-top: 30px; margin-bottom: 30px; position: relative;">
            <div>
                <h2 class="text-center mb-4" style="color: var(--uam-yellow); font-size: 2.0rem; font-weight: bold;">
                    Comparativa
                </h2>
            </div>
            <div style="background-color: var(--uam-white) !important; border-radius: 24px;">
                <div class="row g-4 justify-content-center">
                    <div class="col-12 col-lg-10">
                        <div class="chart-container bg-white rounded-3 shadow-sm p-3" style="height: 400px; max-width: 1200px; margin: 0 auto;">
                            <h5 class="text-center mb-3 fw-bold text-primary">Conteo de Personas</h5>
                            <div class="chart-wrapper" style="position: relative; height: calc(100% - 40px);">
                                <canvas id="conteopersonasChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-10">
                        <div class="chart-container bg-white rounded-3 shadow-sm p-3" style="height: 400px; max-width: 1200px; margin: 0 auto;">
                            <h5 class="text-center mb-3 fw-bold text-primary">Potencia Consumida</h5>
                            <div class="chart-wrapper" style="position: relative; height: calc(100% - 40px);">
                                <canvas id="potenciaconsumidaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="d-flex justify-content-between w-100" style="max-width: 1100px; margin-top: 30px;">
                <a href="../pages/services.php" class="btn btn-uam d-flex align-items-center justify-content-center"
                    style="font-size: 1.4rem; font-weight: bold; border-radius: 15px; width: 180px; height: 50px; padding: 0;">
                    Regresar
                </a>
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="../dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS With Popper-->
    <script src="../dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- Chart JS -->
    <script src="../dist/chart/js/chart.umd.min.js"></script>
    <!-- FontAwesome JS -->
    <script src="../dist/fontawesome/js/all.min.js"></script>
    <!-- MomentJS -->
    <script src="../dist/moment/js/moment.js"></script>
    <script src="../dist/moment/js/moment-timezone-with-data.js"></script>
    <!-- DataTables JS -->
    <script src="../dist/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../dist/datatables/js/dataTables.bootstrap5.min.js"></script>
    <!-- JS -->
    <script src="../js/fourth-bar-line.js"></script>
</body>

</html>