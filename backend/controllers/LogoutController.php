<?php
require_once __DIR__ . '/../services/LogoutService.php';

class LogoutController {
    private $logoutService;

    public function __construct() {
        $this->logoutService = new LogoutService();
    }

    public function handleRequest() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->logoutService->logout();
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Sesión cerrada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo cerrar la sesión']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }
}
?>