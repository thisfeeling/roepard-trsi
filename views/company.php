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
        // Verifica el rol del usuario
        if ($role_id === 2) { // Verifica si el usuario es admin (role_id = 2)
            // Se queda en manage-users.php
        } else {
            // Redirige a index.php si no es admin
            header('Location: ../index.php');
            exit();
        }
    } else {
        // Si no se encuentra el usuario en la base de datos
        header("Location: ../index.php");
        exit();
    }
} else {
    // El usuario no está autenticado, redirige a la página de inicio de sesión
    header("Location: ../index.php");
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>company</title>
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

<body style="background-color: var(--olive-green) !important;">

    <!-- Incluir el navbar -->
    <?php
    include './navbar.php';
    ?>

    <!-- Contenido principal de la página -->
    <main class="container text-light">
        <div class="container">
            <h2 class="my-5">Servicios disponibles: </h2>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <strong>Gestión de Usuarios:</strong>
                <button type="button" class="btn btn-success" id="btnGestionUsuarios" data-bs-toggle="modal" data-bs-target="#btnGestionUsuarios">
                    Ir
                </button>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <strong>More comming soon.</strong>
            </div>
        </div>
    </main>

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
    <script src="../js/main.js"></script>    
</body>

</html>