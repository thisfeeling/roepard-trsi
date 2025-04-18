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
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="./css/style.css">
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
    <!-- Bootstrap Bundle with Popper -->
    <script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>