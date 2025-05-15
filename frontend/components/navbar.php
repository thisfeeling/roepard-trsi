<?php
// Verifica si session_start ya se inicializo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
};

// Detectar si estamos en login.php
$currentFile = basename($_SERVER['PHP_SELF']);
$isLogin = ($currentFile === 'login.php');
$isServices = ($currentFile === 'services.php');
$isCommits = ($currentFile === 'commits.php');
$isUsers = ($currentFile === 'users.php');
$isUserpanel = ($currentFile === 'user-panel.php');
$isVerify = ($currentFile === 'diagnostic.php');
$isFirstArea = ($currentFile === 'first-area.php');
$isSecondBar = ($currentFile === 'second-bar.php');
$isThirdBar = ($currentFile === 'third-bar.php');
$isFourthBarLine = ($currentFile === 'fourth-bar-line.php');

// Variables globales
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$role_id = $_SESSION['role_id'] ?? 'Role not available';  // Obtener el role_id de la sesión
$first_name = $_SESSION['first_name'] ?? 'First name not available';
$last_name = $_SESSION['last_name'] ?? 'Last name not available';

// Asignar nombre de rol
$roles = [
    1 => 'user',
    2 => 'admin',
    3 => 'supervisor',
];
$rol_text = $roles[$role_id] ?? '';

// Si está logeado y no está en services.php, redirigir
if ($loggedIn && $currentFile === 'login.php') {
    header('Location: /trsi/frontend/pages/services.php');
    exit();
}

$pageName = '';
if ($isServices) {
    $pageName = 'Inicio';
} elseif ($isCommits) {
    $pageName = 'Cambios';
} elseif ($isUsers) {
    $pageName = 'Administrar Usuarios';
} elseif ($isUserpanel) {
    $pageName = 'Panel de Usuario';
} elseif ($isVerify) {
    $pageName = 'Diagnóstico';
} elseif ($isFirstArea) {
    $pageName = 'Gráficas';
} elseif ($isSecondBar) {
    $pageName = 'Gráficas';
} elseif ($isThirdBar) {
    $pageName = 'Gráficas';    
} elseif ($isFourthBarLine) {
    $pageName = 'Gráficas';      
} else {
    $pageName = '';
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>navbar</title>
    <!-- Bootstrap CSS -->
    <link href="/trsi/frontend/dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="/trsi/frontend/dist/fontawesome/css/fontawesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="/trsi/frontend/css/style.css">
</head>

<body>
    <header>
        <div class="navbar-uam d-flex align-items-center px-3 py-1">
            <div class="uam-logo">
                <a href="/trsi/index.php">
                    <img src="/trsi/frontend/site/assets/logo.png" alt="Logo UAM" style="height: 80px; width: auto;">
                </a>
            </div>
            <span class="uam-title" style="height: 40px; width: 600px;">Estación de Monitoreo</span>
        </div>

        <div class="container-fluid">
            <div class="uam-bar d-flex align-items-center justify-content-between" style="gap:0;">
                <?php if ($isLogin): ?>
                    <div class="flex-fill text-start d-flex align-items-center justify-content-center">
                        <span class="uam-login" style="font-weight:bold; color:var(--uam-yellow);">Login</span>
                    </div>
                    <div class="flex-fill text-center d-flex align-items-center justify-content-center">
                        <span style="font-weight:bold; color:var(--uam-white);" class="uam-date" id="uam-date"></span>
                    </div>
                    <div class="flex-fill"></div>
                <?php elseif ($loggedIn): ?>
                    <div class="flex-fill text-start d-flex align-items-center justify-content-center">
                        <?php if ($pageName): ?>
                            <span class="uam-login" style="font-weight:bold; color:var(--uam-yellow);">
                                <?php echo $pageName; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="flex-fill text-center d-flex align-items-center justify-content-center">
                        <span style="font-weight:bold; color:var(--uam-white);" class="uam-date" id="uam-date"></span>
                    </div>
                    <div class="flex-fill text-end d-flex align-items-center justify-content-center gap-3">
                        <span class="uam-rol" style="color:var(--uam-yellow); font-weight:bold; font-size:1.3rem;">
                            <?php echo ucfirst($rol_text) . ' ' . htmlspecialchars($first_name); ?>
                        </span>
                        <a href="#" class="btn" style="background:var(--uam-yellow); color:var(--uam-blue); font-weight:bold; border-radius:15px;" id="logout-button">Cerrar sesión</a>
                    </div>
                <?php else: ?>
                    <div class="flex-fill"></div>
                    <div class="flex-fill text-center d-flex align-items-center justify-content-center">
                        <span class="uam-login">Bienvenido</span>
                    </div>
                    <div class="flex-fill"></div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- jQuery -->
    <script src="/trsi/frontend/dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS With Popper-->
    <script src="/trsi/frontend/dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- Chart JS -->
    <script src="/trsi/frontend/dist/chart/js/chart.umd.min.js"></script>
    <!-- FontAwesome JS -->
    <script src="/trsi/frontend/dist/fontawesome/js/all.min.js"></script>
    <!-- MomentJS -->
    <script src="/trsi/frontend/dist/moment/js/moment.js"></script>
    <script src="/trsi/frontend/dist/moment/js/moment-timezone-with-data.js"></script>
    <!-- JS -->
    <script src="/trsi/frontend/js/main.js"></script>
    <script src="/trsi/frontend/js/navbar.js"></script>
</body>

</html>