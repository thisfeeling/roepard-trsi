<?php
// Requiere el controllador para acceder a su clase
require_once __DIR__ . '/../controllers/AuthController.php';

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

// Crear instancia del controlador 
$authController = new AuthController();
// Llama al método que maneja la petición HTTP (POST) y responde en JSON
$authController->login();
?>