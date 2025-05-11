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
    <link rel="website icon" type="png" href="/trsi/assets/logo.png">
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
<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../../frontend/components/navbar.php'; ?>

    <h2>Estado del Sistema TRSI</h2>

    <div class="card">
        <p>🗄️ Base de Datos: 
            <span class="status <?= $db_status ? 'online' : 'offline' ?>">
                <?= $db_status ? 'Conectada' : 'Desconectada' ?>
            </span>
        </p>
    </div>

    <div class="card">
        <p>🤖 Jetson Nano (<?= $jetson_ip ?>): 
            <span class="status <?= $jetson_status ? 'online' : 'offline' ?>">
                <?= $jetson_status ? 'Disponible' : 'Sin respuesta' ?>
            </span>
        </p>
    </div>

    <div class="card">
        <p>👤 Usuario actual: 
            <span class="status">
                <?php
                if ($user_status && isset($user['username'])) {
                    echo htmlspecialchars($user['username']) . " (" . htmlspecialchars($role) . ")";
                } else {
                    echo 'No autenticado';
                }
                ?>
            </span>
        </p>
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
    <!-- Momento JS -->
    <script src="/trsi/frontend/dist/moment/js/moment.js"></script>
    <script src="/trsi/frontend/dist/moment/js/moment-timezone-with-data.js"></script>
    <!-- Sweetalert2 JS -->
    <script src="/trsi/frontend/dist/sweetalert2/js/sweetalert2.all.min.js"></script>
    <!-- JS personalizado -->
    <script src="/trsi/frontend/js/diagnostic.js"></script>
</body>
</html>