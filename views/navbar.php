<?php
// Verifica si session_start ya se inicializo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
};

// Variables globales
$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$role_id = $_SESSION['role_id'] ?? null;  // Obtener el role_id de la sesión
$first_name = $_SESSION['first_name'] ?? 'First name not available';
$last_name = $_SESSION['last_name'] ?? 'Last name not available';
$name = $loggedIn ? $first_name . ' ' . $last_name : "Symphony Synapse";

// Definir los roles con valores numéricos, asumiendo que los valores de role_id son enteros
$userIn = ($role_id === 1);        // Asumiendo que 1 es para usuario
$adminIn = ($role_id === 2);       // Asumiendo que 2 es para admin
$supervisorIn = ($role_id === 3);  // Asumiendo que 3 es para supervisor
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>navbar</title>
    <!-- Bootstrap CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary border-body" data-bs-theme="dark" style="background-color: var(--olive-green) !important;">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <a class="navbar-brand" href="../index.php">
                            <img src="../uploads/TRSIV2.png" alt="" width="50" height="50">
                        </a>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../index.php">Proyect Trsi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../views/home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../views/reviews.php">Reviews</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="../views/about.php">About</a>
                        </li>
                        <?php if ($adminIn): ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="../views/company.php">Company</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="../views/manage-users.php">Manage Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../views/user-panel.php">Admin Panel</a>
                            </li>
                        <?php elseif ($userIn): ?>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../views/user-panel.php">User Panel</a>
                            </li>
                        <?php elseif ($supervisorIn): ?>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../views/user-panel.php">Supervisor Panel</a>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <?php if ($loggedIn): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../backend/LogoutController.php" tabindex="-1">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../views/login.php" tabindex="-1">Sign In</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../views/register.php" tabindex="-1">Sign Up</a>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <?php if ($loggedIn): ?>
                        <li class="nav-item ms-auto text-light">
                            <?php echo $name; ?>
                        </li>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
