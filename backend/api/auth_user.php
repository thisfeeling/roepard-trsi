<?php
require_once __DIR__ . '/../controllers/AuthController.php';

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido'
    ]);
    exit;
}

// Crear instancia del controlador y procesar la autenticación
$authController = new AuthController();
$authController->login();
?>