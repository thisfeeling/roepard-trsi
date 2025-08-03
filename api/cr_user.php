<?php
// Envía la respuesta en formato JSON
header('Content-Type: application/json');
// Requiere el middleware de auth
require_once __DIR__ . '/../middleware/auth.php';

require_once __DIR__ . '/../middleware/status.php';

// Verifica que el usuario esté autenticado y tenga role_id = 2
Auth::checkRole(2);
Status::checkStatus(1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Requiere el controllador para acceder a su clase
require_once __DIR__ . '/../controllers/CrUserController.php';

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido'
    ]);
    exit;
}

// Crear instancia del controlador 
$controller = new CrUserController();
// Llama al método que maneja la petición HTTP (POST) y responde en JSON
$controller->createUser();
?>