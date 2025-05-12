<?php
require_once __DIR__ . '/../services/UserListService.php';

class ListUserController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function handleRequest() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $users = $this->userService->listUsers();
                echo json_encode(['success' => true, 'data' => $users]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Error al obtener usuarios',
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }
}
