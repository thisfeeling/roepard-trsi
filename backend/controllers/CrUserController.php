<?php
// Requiere el servicio 
require_once __DIR__ . '/../services/UserCrService.php';

// Clase controlador
class CrUserController {
    private $userService;

    // Crea una instancia del servicio de usuario
    public function __construct() {
        $this->userService = new UserService();
    }

    public function createUser() {
        try {
            // Recoge los datos del formulario
            $userData = [
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'password' => $_POST['password'] ?? '',
                'country' => $_POST['country'] ?? '',
                'city' => $_POST['city'] ?? '',
                'birthdate' => $_POST['birthdate'] ?? '',
                'status_id' => $_POST['status_id'] ?? '',
                'role_id' => $_POST['role_id'] ?? '',
                'profile_picture' => 'default-profile.png'
            ];

            // Si se sube una imagen de perfil
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
                $uploadDir = __DIR__ . '/../../uploads/';
                $file = $_FILES['profile_picture'];
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newFilename = uniqid('user_') . '.' . $extension;
                $destination = $uploadDir . $newFilename;
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $userData['profile_picture'] = $newFilename;
                }
            }

            $result = $this->userService->createUser($userData);
            $this->sendResponse($result);
        } catch (Exception $e) {
            $this->sendResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
?>