<?php
// Envía la respuesta en formato JSON
header('Content-Type: application/json');
// Requiere el middleware de auth
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../middleware/status.php';

// Verifica que el usuario esté autenticado y tenga role_id = 1,2,3
Auth::checkAnyRole([1, 2, 3]);
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
require_once __DIR__ . '/../controllers/DetUserController.php';

try {
    // Solo aceptar POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            'status' => 'error',
            'message' => 'Método no permitido'
        ]);
        exit;
    }

    // Crea una instancia del controlador
    $controller = new DetUserController();
    // Llama al método que maneja la petición HTTP (POST) y responde en JSON
    $controller->getUserDetails();
} catch (Throwable $e) {
    // Envía la respuesta en formato JSON
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error interno: ' . $e->getMessage()
    ]);
}
?>