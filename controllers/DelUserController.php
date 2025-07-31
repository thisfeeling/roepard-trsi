<?php
// Requiere el servicio 
require_once __DIR__ . '/../services/UserDeleteService.php';

class DelUserController {
    private $userService;

    // Crea una instancia del servicio de usuario
    public function __construct() {
        $this->userService = new UserService();
    }

    // Este método maneja la petición HTTP
    public function handleRequest() {
        // Indica que la respuesta será en formato JSON
        header('Content-Type: application/json');

        // Solo permite peticiones POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene el ID del usuario a eliminar desde los datos enviados por POST
            $user_id = $_POST['user_id'] ?? null;

            if ($user_id) {
                // Llama al servicio para eliminar el usuario
                $success = $this->userService->deleteUser($user_id);

                // Devuelve la respuesta en JSON según el resultado
                if ($success) {
                    echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el usuario']);
                }
            } else {
                // Si no se proporcionó un ID de usuario
                echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado']);
            }
        } else {
            // Si la petición no es POST, devuelve error
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }
}