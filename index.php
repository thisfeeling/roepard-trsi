<?php
// Incluir el archivo de conexión a la base de datos
require_once './backend/core/DBConfig.php';
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
        header('Location: /trsi/frontend/pages/services.php');
        exit();
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
    <title>index</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/trsi/frontend/dist/bootstrap/css/bootstrap.css">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="/trsi/frontend/dist/fontawesome/css/fontawesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="/trsi/frontend/css/style.css">
    <!-- Icon of the page -->
    <!-- Manifest -->
    <link rel="manifest" href="/trsi/site.webmanifest">

    <!-- Mobile & PWA support -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="trsi">
    <meta name="apple-mobile-web-app-title" content="trsi">
    <meta name="theme-color" content="#3393FF">
    <meta name="msapplication-navbutton-color" content="#3393FF">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="/index.php">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Íconos para PWA y dispositivos -->
    <link rel="icon" type="image/png" sizes="16x16" href="./favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./favicon-32x32.png">
    <link rel="shortcut icon" href="./favicon.ico">
    <link rel="apple-touch-icon" sizes="32x32" href="./apple-touch-icon.png">
</head>
<body style="background-color: var(--olive-green) !important;">
    
    <!-- Spinner de carga -->
    <main class="d-flex justify-content-center align-items-center vh-100">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </main>

    <!-- Script para redirigir despues de 1 segundos -->
    <script>
        setTimeout(function () {
            window.location.href = "<?php echo htmlspecialchars($redirect_url); ?>";
        }, 1000); // 1000 ms = 1 segundos
    </script>
    <!-- jQuery -->
    <script src="/trsi/frontend/dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS With Popper-->
    <script src="/trsi/frontend/dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- Chart JS -->
    <script src="/trsi/frontend/dist/chart/js/chart.umd.min.js"></script>
    <!-- FontAwesome JS -->
    <script src="/trsi/frontend/dist/fontawesome/js/all.min.js"></script>
</body>
</html>