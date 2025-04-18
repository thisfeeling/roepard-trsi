<?php
// Incluir el archivo de conexión a la base de datos
require_once '../backend/DBConfig.php';
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
        header("Location: ../index.php");
        exit();
    }
    // Reridige si esta logeado al panel se usuario
    if (isset($_SESSION['role_id'])) {
        switch ($_SESSION['role_id']) {
            case '1':
                header('Location: ./user-panel.php');
                exit();
            case '2':
                header('Location: ./user-panel.php');
                exit();
            case '3':
                header('Location: ./user-panel.php');
                exit();
            case '4':
                header('Location: ./user-panel.php');
                exit();
            case '5':
                header('Location: ./user-panel.php');
                exit();
            case '6':
                header('Location: ./user-panel.php');
                exit();
            default:
                header('Location: ../index.php'); // Redirige a index si el rol no coincide
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
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- Icon of the page -->
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
</head>

<body style="background-color: var(--olive-green);">

    <!-- Incluir el navbar -->
    <?php
    include './navbar.php';
    ?>

    <main style="background-color: var(--olive-green);">
        <div class="container d-flex justify-content-center align-items-center vh-100">
            <!-- Form Card -->
            <div class="card shadow-lg p-4" style="max-width: 30rem; background-color: var(--soft-green); color: var(--dark-green);">
                <div class="card-header text-center border-0 pb-0" style="background-color: transparent;">
                    <h2 class="text-dark">Login Form</h2>
                </div>
                <div class="card-body">
                    <form id="LoginForm" class="needs-validation" novalidate>
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Email, Username or Phone</label>
                            <input type="email" class="form-control custom-input" id="email" name="email" placeholder="Email/Username/Phone" required autocomplete="email">
                            <div class="invalid-feedback">Please enter a valid input.</div>
                        </div>
                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control custom-input" id="password" name="password" placeholder="Password" required autocomplete="current-password">
                            <div class="invalid-feedback">Password is required.</div>
                        </div>
                        <!-- Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary custom-button">Sign In</button>
                            <small class="text-body-secondary my-2">By clicking Sign In, you agree to the terms of use.</small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal de login -->
        <div class="modal fade" id="LoginModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true" data-bs-theme="white">
            <div class="modal-dialog">
                <div class="modal-content bg-white text-dark" style="background-color: var(--olive-green);">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">title</h5>
                        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        content
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Incluir el footer -->
    <?php
    include './footer.php';
    ?>

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="../js/bootstrap.bundle.min.js"></script>
    <!-- main JS -->
    <script src="../js/login.js"></script>
</body>

</html>