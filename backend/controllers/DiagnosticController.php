<?php
require_once __DIR__ . '/../services/DiagnosticService.php';

class DiagnosticController {
    private $diagnosticService;

    public function __construct() {
        $this->diagnosticService = new DiagnosticService();
    }

    public function handleRequest() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'db') {
                $result = $this->diagnosticService->getDiagnostic();
                echo json_encode($result);
            } else {
                echo json_encode(['error' => 'Acción no válida']);
            }
        } else {
            echo json_encode(['error' => 'Método no permitido']);
        }
    }
}