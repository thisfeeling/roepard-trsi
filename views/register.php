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
    <title>register</title>
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

    <!-- Navbar -->
    <?php include './navbar.php'; ?>

    <main class="d-flex justify-content-center align-items-center vh-100">
        <div class="container">
            <!-- Form Card -->
            <div class="card shadow-lg mx-auto" style="max-width: 30rem; background-color: var(--soft-green); color: var(--dark-green);">
                <div class="card-header text-center border-0 pb-0" style="background-color: transparent;">
                    <h2 class="text-dark">Register Form</h2>
                </div>
                <div class="card-body">

                    <form id="RegisterForm" class="row g-4 needs-validation" novalidate>

                        <!-- First Name -->
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter a first name" required autocomplete="given-name">
                            <div class="invalid-feedback">Please enter a first name.</div>
                        </div>

                        <!-- Last Name -->
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter a last name" required autocomplete="family-name">
                            <div class="invalid-feedback">Please enter a last name.</div>
                        </div>

                        <!-- Phone Picker -->
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter a phone" required autocomplete="phone">
                            <div class="invalid-feedback">Please enter a phone.</div>
                        </div>

                        <!-- Country -->
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" placeholder="Enter a country" required autocomplete="country">
                            <div class="invalid-feedback">Please enter a country.</div>
                        </div>

                        <!-- City -->
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="Enter a city" required autocomplete="country-name">
                            <div class="invalid-feedback">Please enter a city.</div>
                        </div>

                        <!-- Birthdate -->
                        <div class="col-md-6">
                            <label for="birthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control" id="birthdate" name="birthdate" required autocomplete="birthdate">
                            <div class="invalid-feedback">Please enter your birthdate.</div>
                        </div>

                        <!-- Username -->
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter a username" required autocomplete="nickname">
                            <div class="invalid-feedback">Please enter a valid username.</div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter an email" required autocomplete="email">
                            <div class="invalid-feedback">Please enter a valid email.</div>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter a password" required autocomplete="off">
                            <div class="invalid-feedback">Password is required.</div>
                        </div>


                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label for="status_id" class="form-label">Status</label>
                            <!-- Select deshabilitado -->
                            <select class="form-select" id="status_id" name="status_id" disabled>
                                <option value="1" selected>Active</option>
                                <option value="2">Inactive</option>
                                <option value="3">Filed</option>
                            </select>
                            <!-- Campo oculto que envía el valor -->
                            <input type="hidden" id="status_id_hidden" name="status_id" value="1">
                        </div>


                        <!-- Terms and Conditions -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" required autocomplete="off">
                                <label class="form-check-label" for="agreeTerms">
                                    Agree to terms and conditions
                                </label>
                                <a href="../views/terms.php" target="_blank" class="ms-2 text-decoration-none">View terms</a>
                                <div class="invalid-feedback">You must agree before submitting.</div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal de register -->
        <div class="modal fade" id="RegisterModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true" data-bs-theme="white">
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

    <!-- Footer -->
    <?php include './footer.php'; ?>

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="../js/bootstrap.bundle.min.js"></script>
    <!-- register JS -->
    <script src="../js/register.js"></script>
</body>

</html>