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
    } else {
        // Si no se encuentra el usuario en la base de datos
        header("Location: /trsi/index.php");
        exit();
    }
    // Reridige si esta logeado al panel se usuario
    if (isset($_SESSION['role_id'])) {
        switch ($_SESSION['role_id']) {
            case '1':
                header('Location: /trsi/frontend/pages/user-panel.php');
                exit();
            case '2':
                header('Location: /trsi/frontend/pages/user-panel.php');
                exit();
            case '3':
                header('Location: /trsi/frontend/pages/user-panel.php');
                exit();
            default:
                header('Location: /trsi/index.php'); // Redirige a index si el rol no coincide
                exit();
        }
    };
} else {
    // El usuario no está autenticado mantiene en register.php
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>login</title>
    <!-- Bootstrap CSS -->
    <link href="/trsi/frontend/dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="/trsi/frontend/dist/fontawesome/css/fontawesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="/trsi/frontend/css/style.css">
    <link rel="stylesheet" href="/trsi/frontend/css/variables.css">
    <!-- Icono -->
    <link rel="icon" href="/trsi/favicon.ico" type="image/x-icon">
</head>

<body style="background-color: var(--uam-white);">

    <!-- Navbar -->
    <?php include __DIR__ . '/../../frontend/components/navbar.php'; ?>


    <main style="background-color: var(--uam-white);">
        <div class="d-flex justify-content-center align-items-center" style="min-height: 0vh;">
            <!-- Form Card -->
            <div class="card shadow-lg p-4"
                style="max-width: 30rem; background-color: var(--uam-blue); color: var(--uam-white);">
                <div class="card-header text-center border-0 pb-0" style="background-color: transparent;">
                    <h2 style="color: var(--uam-yellow)">Formulario de login</h2>
                </div>
                <div class="card-body">

                    <div class="login-form-container mx-auto"
                        style="font-weight: bold; background-color: var(--uam-blue); color: var(--uam-white); max-width: 300px; max-height: 250px;">
                        <form id="LoginForm" class="needs-validation"
                            style="background-color: var(--uam-blue); color: var(--uam-white);" novalidate>
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label">Email, Username or Phone</label>
                                <input style="color: var(--uam-black);" type="email" class="form-control custom-input"
                                    id="email" name="email" placeholder="Email/Username/Phone" required
                                    autocomplete="email">
                                <div class="invalid-feedback">Porfavor ingree un input valido.</div>
                            </div>
                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input style="color: var(--uam-black);" type="password"
                                    class="form-control custom-input" id="password" name="password"
                                    placeholder="Password" required autocomplete="current-password">
                                <div class="invalid-feedback">Porfavor ingree un input valido.</div>
                            </div>
                            <!-- Submit -->
                            <div class="d-grid">
                                <button style="background-color: var(--uam-yellow); color: var(--uam-blue);"
                                    type="submit" class="btn btn-primary custom-button">Iniciar sesión</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal de login -->
        <div class="modal fade" id="LoginModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true"
            data-bs-theme="white">
            <div class="modal-dialog">
                <div class="modal-content bg-white text-dark" style="background-color: var(--olive-green);">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Titulo</h5>
                        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Contenido
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="/trsi/frontend/dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS With Popper-->
    <script src="/trsi/frontend/dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- Chart JS -->
    <script src="/trsi/frontend/dist/chart/js/chart.umd.min.js"></script>
    <!-- FontAwesome JS -->
    <script src="/trsi/frontend/dist/fontawesome/js/all.min.js"></script>
    <!-- main JS -->
    <script src="/trsi/frontend/js/login.js"></script>
</body>

</html>