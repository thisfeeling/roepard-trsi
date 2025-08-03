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
        // se queda en services.php si esta autenticado
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

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>services</title>
    <!-- Bootstrap CSS -->
    <link href="../dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="../dist/fontawesome/css/fontawesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/variables.css">
    <!-- Icono -->
    <link rel="icon" href="../favicon.ico" type="image/x-icon">

</head>

<body style="background-color: var(--uam-white) !important;">

    <!-- Navbar -->
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <!-- Modal de Sistema -->
    <div class="modal fade" id="sistemaModal" tabindex="-1" aria-labelledby="sistemaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-uam" style="color: var(--uam-blue);">
                    <h5 class="modal-title" id="sistemaModalLabel">Información del Sistema</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="fw-bold text-uam mb-3" style="color: var(--uam-blue);">Estado del Sistema
                                </h6>
                                <div class="list-group">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-bold" style="color: var(--uam-blue);">Versión del
                                            Sistema</span>
                                        <span id="github-version"
                                            class="d-flex align-items-center justify-content-center"
                                            style="color: var(--uam-yellow); font-size: 1.2rem; font-weight: bold; border-radius: 15px; width: 220px; height: 50px; padding: 0;">
                                            Cargando...
                                        </span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="fw-bold" style="color: var(--uam-blue);">Estado de la Base de
                                            Datos</span>
                                        <div id="db-status-container" class="d-flex align-items-center">
                                            <span id="db-status-text" class="me-2">Verificando...</span>
                                            <div id="db-status-indicator"
                                                class="spinner-border spinner-border-sm text-warning" role="status">
                                                <span class="visually-hidden">Cargando...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <h6 class="fw-bold text-uam" style="color: var(--uam-blue);">Información del
                                            Usuario Conectado</h6>
                                        <div class="small">
                                            <div class="fw-bold" style="color: var(--uam-yellow);">Nombre:
                                                <span
                                                    style="color: var(--uam-blue);"><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></span>
                                            </div>
                                            <div class="fw-bold" style="color: var(--uam-yellow);">Correo:
                                                <span
                                                    style="color: var(--uam-blue);"><?php echo htmlspecialchars($email); ?></span>
                                            </div>
                                            <div class="fw-bold" style="color: var(--uam-yellow);">Rol:
                                                <span style="color: var(--uam-blue);"><?php
                                                $rol_text = '';
                                                if ($role_id == 2)
                                                    $rol_text = 'Admin';
                                                elseif ($role_id == 3)
                                                    $rol_text = 'Supervisor';
                                                else
                                                    $rol_text = 'User';
                                                echo htmlspecialchars($rol_text);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal de la página -->
    <main class="container-fluid d-flex flex-column align-items-center justify-content-center"
        style="min-height: 80vh;">
        <div class="panel-usuario p-5"
            style="background: var(--uam-blue); border-radius: 24px; width: 90%; max-width: 1100px; margin-top: 30px; margin-bottom: 30px; position: relative;">
            <h2 class="text-center mb-4" style="color: var(--uam-yellow); font-size: 2.0rem; font-weight: bold;">
                <?php echo ucfirst($rol_text ?? 'User'); ?> Panel
            </h2>
            <div class="row">
                <!-- Columna de gráficas -->
                <div class="col-md-6 d-flex flex-column align-items-center">
                    <h4 class="mb-3" style="color: #fff; font-weight: bold;">Opciones de Gráficas</h4>
                    <a href="../pages/first-area.php" class="btn btn-uam mb-3 w-100">Datos en Tiempo
                        Real</a>
                    <a href="../pages/second-bar.php" class="btn btn-uam mb-3 w-100">Gráficas de
                        Potencia</a>
                    <a href="../pages/third-bar.php" class="btn btn-uam mb-3 w-100">Gráficas de Conteo</a>
                    <a href="../pages/fourth-bar-line.php" class="btn btn-uam mb-3 w-100">Comparativa</a>
                </div>
                <!-- Columna de servicios -->
                <div class="col-md-6 d-flex flex-column align-items-center">
                    <h4 class="mb-3" style="color: #fff; font-weight: bold;">Opciones de Servicios</h4>
                    <?php if ($role_id == 2): // Admin ?>
                    <a href="../pages/users.php" class="btn btn-uam mb-3 w-100">Adminstrador de Usuarios</a>
                    <a href="../pages/user-panel.php" class="btn btn-uam mb-3 w-100">Panel de usuario</a>
                    <a href="../pages/diagnostic.php" class="btn btn-uam mb-3 w-100">Verificar
                        Conexiones</a>
                    <a href="../pages/commits.php" class="btn btn-uam mb-3 w-100">Registro de Cambios</a>
                    <?php elseif ($role_id == 3): // Supervisor ?>
                    <a href="../pages/user-panel.php" class="btn btn-uam mb-3 w-100">Panel de usuario</a>
                    <a href="../pages/diagnostic.php" class="btn btn-uam mb-3 w-100">Verificar
                        Conexiones</a>
                    <a href="../pages/commits.php" class="btn btn-uam mb-3 w-100">Registro de Cambios</a>
                    <?php else: // Usuario ?>
                    <a href="../pages/user-panel.php" class="btn btn-uam mb-3 w-100">Panel de usuario</a>
                    <a href="../pages/diagnostic.php" class="btn btn-uam mb-3 w-100">Verificar
                        Conexiones</a>
                    <a href="../pages/commits.php" class="btn btn-uam mb-3 w-100">Registro de Cambios</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex justify-content-between w-100" style="max-width: 1100px;">
                <button type="button" class="btn btn-uam d-flex align-items-center justify-content-center"
                    style="font-size: 1.4rem; font-weight: bold; border-radius: 15px; width: 220px; height: 50px; padding: 0;"
                    id="btnSistema">
                    Sistema
                </button>
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
    <!-- JS personalizado -->
    <script src="../js/main.js"></script>
    <script src="../js/services.js"></script>
</body>

</html>