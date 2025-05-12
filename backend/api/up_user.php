<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../middleware/auth.php';

// Verifica que el usuario esté autenticado y tenga role_id = 1,2,3
Auth::checkAnyRole([1, 2, 3]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}
require_once __DIR__ . '/../controllers/UpUserController.php';

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido'
    ]);
    exit;
}

$is_admin = isset($_POST['is_admin']) && $_POST['is_admin'] == '1';

if (!$is_admin) {
    if (empty($_POST['current_password'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'La contraseña actual es obligatoria'
        ]);
        exit;
    }
    // OBTÉN EL USUARIO ANTES DE VALIDAR LA CONTRASEÑA
    require_once __DIR__ . '/../models/UserDetails.php';
    $userDetails = new UserDetails();
    $user = $userDetails->findById($_POST['user_id']);

    if (!$user || !password_verify($_POST['current_password'], $user['password'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'La contraseña actual es incorrecta'
        ]);
        exit;
    }
}
// Si es admin, NO valida la contraseña actual y sigue con la actualización

// Crear instancia del controlador y procesar la actualización
$controller = new UpUserController();
$controller->updateUser();
exit;
?>