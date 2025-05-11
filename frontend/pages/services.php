<?php
// Incluir el archivo de conexión a la base de datos
require_once __DIR__ . '/../../backend/core/DBConfig.php';
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
        $name = $first_name . ' ' . $last_name;
        // se queda en services.php
    } else {
        // Si no se encuentra el usuario en la base de datos
        header("Location: /trsi/frontend/pages/login.php");
        exit();
    }
} else {
    // El usuario no está autenticado, redirige a la página de inicio de sesión
    header("Location: /trsi/frontend/pages/login.php");
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>services</title>
    <!-- Bootstrap CSS -->
    <link href="/trsi/frontend/dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="/trsi/frontend/dist/fontawesome/css/fontawesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="/trsi/frontend/css/style.css">
    <link rel="stylesheet" href="/trsi/frontend/css/variables.css">
</head>

<body style="background-color: var(--olive-green) !important;">

    <!-- Navbar -->
    <?php include __DIR__ . '/../../frontend/components/navbar.php'; ?>

    <!-- Contenido principal de la página -->
    <main class="container-fluid d-flex flex-column align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="panel-usuario p-5" style="background: var(--uam-blue); border-radius: 24px; width: 90%; max-width: 1100px; margin-top: 30px; margin-bottom: 30px; position: relative;">
            <h2 class="text-center mb-4" style="color: var(--uam-yellow); font-size: 2.0rem; font-weight: bold;">
                <?php echo ucfirst($rol_text ?? 'User'); ?> Panel
            </h2>
            <div class="row">
                <!-- Columna de gráficas -->
                <div class="col-md-6 d-flex flex-column align-items-center">
                    <h4 class="mb-3" style="color: #fff; font-weight: bold;">Graph Options</h4>
                    <a href="/trsi/frontend/pages/" class="btn btn-uam mb-3 w-100">Datos en Tiempo Real</a>
                    <a href="/trsi/frontend/pages/" class="btn btn-uam mb-3 w-100">Gráficas de Potencia</a>
                    <a href="/trsi/frontend/pages/" class="btn btn-uam mb-3 w-100">Gráficas de Conteo</a>
                    <a href="/trsi/frontend/pages/" class="btn btn-uam mb-3 w-100">Comparativa</a>
                </div>
                <!-- Columna de servicios -->
                <div class="col-md-6 d-flex flex-column align-items-center">
                    <h4 class="mb-3" style="color: #fff; font-weight: bold;">Services Options</h4>
                    <a href="/trsi/frontend/pages/users.php" class="btn btn-uam mb-3 w-100">Adminstrador de Usuarios</a>
                    <a href="/trsi/frontend/pages/user-panel.php" class="btn btn-uam mb-3 w-100">Panel de usuario</a>
                    <a href="/trsi/frontend/pages/diagnostic.php" class="btn btn-uam mb-3 w-100">Verificar Conexiones</a>
                    <a href="/trsi/frontend/pages/commits.php" class="btn btn-uam mb-3 w-100">Registro de Cambios</a>
                </div>
            </div>
            <span id="github-version" class="position-absolute" style="bottom: 20px; right: 30px; color: var(--uam-yellow); font-weight: bold;">
                Rev-cargando...
            </span>
        </div>
    </main>

    </main>

    <!-- jQuery -->
    <script src="/trsi/frontend/dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS With Popper-->
    <script src="/trsi/frontend/dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- Chart JS -->
    <script src="/trsi/frontend/dist/chart/js/chart.umd.min.js"></script>
    <!-- FontAwesome JS -->
    <script src="/trsi/frontend/dist/fontawesome/js/all.min.js"></script>
    <!-- JS personalizado -->
    <script src="/trsi/frontend/js/main.js"></script>
    <script src="/trsi/frontend/js/services.js"></script>
</body>

</html>