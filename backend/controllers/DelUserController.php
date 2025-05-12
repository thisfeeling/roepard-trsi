<?php
require_once __DIR__ . '/../services/UserService.php';

class DelUserController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function handleRequest() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'] ?? null;
            if ($user_id) {
                $success = $this->userService->deleteUser($user_id);
                if ($success) {
                    echo json_encode(['success' => true, 'message' => 'Usuario eliminado']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el usuario']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }
}
?>