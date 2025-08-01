<?php
// Requiere el servicio y el modelo
require_once __DIR__ . '/../services/UserUpdateService.php';
require_once __DIR__ . '/../models/UserDetails.php';

// Clase controlador
class UpUserController {
    private $userService;

    // Crea una instancia del servicio de usuario
    public function __construct() {
        $this->userService = new UserService();
    }

    // Este método maneja la petición HTTP
    public function updateUser() {
        try {
            // Validar datos de entrada
            $this->validateInput();

            // Procesar la imagen si se subió una nueva
            $profilePicture = $this->handleProfilePicture();

            // Preparar datos del usuario
            $userData = [
                'user_id' => $_POST['user_id'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'password' => $_POST['password'] ?? '',
                'current_password' => $_POST['current_password'] ?? '',
                'country' => $_POST['country'],
                'city' => $_POST['city'],
                'birthdate' => $_POST['birthdate'],
                'status_id' => $_POST['status_id'],
                'role_id' => $_POST['role_id'],
                'profile_picture' => $profilePicture,
                'is_admin' => isset($_POST['admin_edit']) && $_POST['admin_edit'] == '1'
            ];

            // Obtener usuario actual
            $userDetails = new UserDetails();
            $user = $userDetails->findById($_POST['user_id']);

            $is_admin = isset($_POST['is_admin']) && $_POST['is_admin'] == '1';

            if (!$is_admin) {
                if (empty($_POST['current_password'])) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'La contraseña actual es obligatoria'
                    ]);
                    exit;
                }

                if (!$user || !password_verify($_POST['current_password'], $user['password'])) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'La contraseña actual es incorrecta'
                    ]);
                    exit;
                }
            }

            // Actualizar usuario
            $result = $this->userService->updateUser($userData);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Usuario actualizado correctamente.'
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    // Validar datos de entrada
    private function validateInput() {
        $requiredFields = [
            'user_id', 'first_name', 'last_name', 'username', 'email',
            'phone', 'country', 'city', 'birthdate',
            'status_id', 'role_id'
        ];

        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("El campo '$field' es obligatorio.");
            }
        }
    }

    // Procesar la imagen si se subió una nueva
    private function handleProfilePicture() {
        $uploadDir = __DIR__ . '/../uploads/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/heic'];
        
        // Obtener imagen actual
        $currentPicture = $this->userService->getCurrentProfilePicture($_POST['user_id']);
        
        if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== 0) {
            return $currentPicture;
        }

        $file = $_FILES['profile_picture'];
        $fileType = mime_content_type($file['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception('Formato de imagen no permitido.');
        }

        // Eliminar imagen anterior si existe
        if ($currentPicture !== 'default-profile.png' && file_exists($uploadDir . $currentPicture)) {
            unlink($uploadDir . $currentPicture);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFilename = "user_" . $_POST['user_id'] . '.' . $extension;
        $destination = $uploadDir . $newFilename;
        
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('Error al subir la imagen.');
        }

        return $newFilename;
    }
}
?>