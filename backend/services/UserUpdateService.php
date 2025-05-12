<?php
require_once __DIR__ . '/../models/UserUpdate.php';
require_once __DIR__ . '/../models/UserDetails.php';

class UserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserUpdate();
    }

    public function updateUser($userData) {
        // Validar contraseña actual si no es admin
        if (!$userData['is_admin']) {
            if (empty($userData['current_password'])) {
                throw new Exception("La contraseña actual es obligatoria.");
            }
            
            if (!$this->userModel->verifyCurrentPassword($userData['user_id'], $userData['current_password'])) {
                throw new Exception("La contraseña actual es incorrecta.");
            }
        }

        // Procesar contraseña
        if (!empty($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        } else {
            $userData['password'] = $this->userModel->getCurrentPassword($userData['user_id']);
        }

        // Actualizar usuario
        $result = $this->userModel->update($userData);
        
        if (!$result) {
            throw new Exception("Error al actualizar el usuario.");
        }

        return [
            'status' => 'success',
            'message' => 'Usuario actualizado correctamente.'
        ];
    }

    public function getCurrentProfilePicture($userId) {
        return $this->userModel->getProfilePicture($userId);
    }

    public function getUserById($user_id) {
        $userDetails = new UserDetails();
        return $userDetails->findById($user_id);
    }
}
?>