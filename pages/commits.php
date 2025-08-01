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
    $stmt = $db->prepare("SELECT profile_picture, first_name, last_name, email, phone, username, country, city, birthdate, role_id, status_id FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    // Si el usuario existe, obtenemos sus datos
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $profile_picture = $user['profile_picture'];
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
        $email = $user['email'];
        $phone = $user['phone'];
        $username = $user['username'];
        $country = $user['country'];
        $city = $user['city'];
        $birthdate = $user['birthdate'];
        $role_id = $user['role_id'];
        $status_id = $user['status_id'];
        $name = $first_name . ' ' . $last_name;
        // Si se encuentra autenticado , mantiene en la pagina
    } else {
        // Si no se encuentra el usuario en la base de datos
        header("Location: ../index.html");
        exit();
    }
} else {
    // El usuario no está autenticado, redirige a la página de index.php
    header("Location: ../index.html");
    exit();
}
?>

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
        <div class="bg-dark table-responsive p-4 mb-4" style="border-radius: 18px; width: 100%; max-width: 1100px;">
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