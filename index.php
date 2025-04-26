<?php
// Incluir el archivo de conexion a la base de datos
require_once './backend/DBConfig.php';
// Crear una instancia de la clase de conexion
$auth = new DBConfig();
$db = $auth->getConnection();
// Verifica si la sesion esta iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Logica de redireccion basada en rol
$redirect_url = './views/home.php'; // URL por defecto
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $user_id = $_SESSION['user_id'];
    $stmt = $db->prepare("SELECT first_name, last_name, email, role_id FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $role_id = $user['role_id'];
        // Verifica si el usuario es administrador
        $redirect_url = ($role_id === 2) ? './views/company.php' : './views/home.php';
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>index</title>
    <!-- Bootstrap CSS -->
    <link href="./dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="./css/style.css">
    <!-- Icon of the page -->
    <!-- Manifest -->
    <link rel="manifest" href="./site.webmanifest">

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
    <link rel="icon" type="image/png" sizes="96x96" href="./favicon-96x96.png">
    <link rel="icon" type="image/svg+xml" href="./favicon.svg">
    <link rel="apple-touch-icon" sizes="180x180" href="./apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="57x57" href="./apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="./apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="./apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="./apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="./apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="./apple-touch-icon-180x180.png">

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

    <!-- Script para redirigir despues de 2 segundos -->
    <script>
        setTimeout(function () {
            window.location.href = "<?php echo htmlspecialchars($redirect_url); ?>";
        }, 2000); // 2000 ms = 2 segundos
    </script>
    <!-- jQuery -->
    <script src="./js/jquery.js"></script>
    <!-- Bootstrap JS With Popper-->
    <script src="./dist/bootstrap/js/bootstrap.bundle.js"></script>
</body>
</html>