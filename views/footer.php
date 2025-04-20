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

// Definir los roles con valores numericos
$userIn = ($role_id === 1);        // 1 es para usuario
$adminIn = ($role_id === 2);       // 2 es para admin
$supervisorIn = ($role_id === 3);  // 3 es para supervisor
?>

<!doctype html>
<html lang="en" class="text-dark" style="background-color: var(--olive-green) !important;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>footer</title>
    <!-- Bootstrap CSS -->
    <link href="../dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <footer class="bg-dark text-dark" style="background-color: var(--olive-green) !important;">
        <div class="container">
            <footer class="py-5 bg-dark text-dark" style="background-color: var(--olive-green) !important;">
                <div class="row">
                    <!-- Opciones generales -->
                    <div class="col-6 col-md-2 mb-3">
                        <h5>
                            Support
                        </h5>
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2"><a href="" class="nav-link p-0 text-light">Esteban Manrique</a></li>
                        </ul>
                    </div>

                    <!-- Opciones dinamicas basadas en el rol -->
                    <?php if ($loggedIn): ?>
                        <div class="container">
                            <div class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
                                <div class="col-md-4 d-flex align-items-center">
                                    <span class="mb-3 mb-md-0 text-body-secondary">© 2025 Trsi, Inc. All rights reserved.</span>
                                </div>
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="../views/terms.php">Terms</a>
                                </li>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-wrap justify-content-between align-items-center  py-3 my-4 bg-dark text-dark" data-bs-theme="dark" style="background-color: var(--olive-green) !important;">
                            <p>© 2025 Trsi, Inc. All rights reserved.</p>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../views/terms.php">Terms</a>
                            </li>
                        </div>
                    <?php endif; ?>
                </div>

            </footer>
        </div>
    </footer>
    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
    <!-- Bootstrap JS -->
    <script src="../dist/bootstrap/js/bootstrap.js"></script>
</body>

</html>