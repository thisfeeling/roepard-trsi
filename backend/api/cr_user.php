<?php
require_once __DIR__ . '/../middleware/auth.php';

// Verifica que el usuario esté autenticado y tenga role_id = 2
Auth::checkRole(2);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

require_once __DIR__ . '/../controllers/CrUserController.php';

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido'
    ]);
    exit;
}

$controller = new CrUserController();
$controller->createUser();
?>