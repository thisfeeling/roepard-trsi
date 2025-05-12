<?php
// Incluir el archivo de conexión a la base de datos
require_once __DIR__ . '/../../backend/core/DBConfig.php';
// Crear una instancia de la clase de conexión
$auth = new DBConfig();
$db = $auth->getConnection();
$pdo = $db; // Usar la instancia ya creada

// Verifica si la sesión está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar conexión base de datos
$db_status = false;
try {
    $stmt = $pdo->query("SELECT 1");
    $db_status = $stmt ? true : false;
} catch (PDOException $e) {
    $db_status = false;
}

// Verificar Jetson Nano (suponiendo que responde a una IP o endpoint)
$jetson_ip = '192.168.1.100'; // cambia por IP real
$jetson_status = false;
$ping_result = shell_exec("ping -c 1 -W 1 $jetson_ip");

if (strpos($ping_result, '1 received')) {
    $jetson_status = true;
}

// Verificar usuario y permisos
$user = null;
$user_status = false;
$role = 'N/A';
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT users.username, roles.role_name FROM users 
                           JOIN roles ON users.role_id = roles.role_id 
                           WHERE users.user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $user_status = true;
        $role = $user['role_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>verify</title>
    <link rel="website icon" type="png" href="/trsi/frontend/site/assets/logo.png">
    <!-- Bootstrap 5 CSS -->
    <link href="/trsi/frontend/dist/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Sweetalert2 CSS -->
    <link rel="stylesheet" href="/trsi/frontend/dist/sweetalert2/css/sweetalert2.min.css">
    <!-- DataTables Bootstrap 5 CSS -->
    <link href="/trsi/frontend/dist/datatables/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="/trsi/frontend/dist/fontawesome/css/fontawesome.min.css" />
    <!-- Variables y estilos -->
    <link rel="stylesheet" href="/trsi/frontend/css/variables.css" />
    <link rel="stylesheet" href="/trsi/frontend/css/style.css" />
</head>
<body class="bg-white text-light">
    <!-- Navbar -->
    <?php include __DIR__ . '/../../frontend/components/navbar.php'; ?>

    <div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 90vh;">
        <div class="uam-bar-commits w-100" style="max-width: 700px;">
            <h2 class="text-center" style="color: var(--uam-yellow); font-weight: bold;">Verificar Conexiones</h2>
            
            <div class="d-flex flex-column align-items-center gap-3 mt-4">
                <button id="btn-db" class="btn btn-uam w-100" style="max-width: 350px;">Verificar Base de datos</button>
                <div id="db-result" class="mt-2 mb-3" style="font-size: 1.3rem; font-weight: bold;"></div>
                
                <button id="btn-jetson" class="btn btn-uam w-100" style="max-width: 350px;">Verificar Dispositivo</button>
                <div id="jetson-result" class="mt-2 mb-3" style="font-size: 1.3rem; font-weight: bold;"></div>
            </div>
            
            <div class="mt-4">
                <a href="/trsi/frontend/pages/services.php" class="btn btn-uam" style="font-size: 1.2rem; border-radius: 15px; width: 150px;">Regresar</a>
            </div>
        </div>
    </div>

    <!-- Scripts igual que antes -->
    <script src="/trsi/frontend/dist/jquery/js/jquery.min.js"></script>
    <script src="/trsi/frontend/dist/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="/trsi/frontend/dist/chart/js/chart.umd.min.js"></script>
    <script src="/trsi/frontend/dist/fontawesome/js/all.min.js"></script>
    <script src="/trsi/frontend/dist/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/trsi/frontend/dist/datatables/js/dataTables.bootstrap5.min.js"></script>
    <script src="/trsi/frontend/dist/moment/js/moment.js"></script>
    <script src="/trsi/frontend/dist/moment/js/moment-timezone-with-data.js"></script>
    <script src="/trsi/frontend/dist/sweetalert2/js/sweetalert2.all.min.js"></script>
    <script src="/trsi/frontend/js/diagnostic.js"></script>
</body>
</html>