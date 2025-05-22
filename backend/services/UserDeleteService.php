<?php
// Requiere el modelo
require_once __DIR__ . '/../models/UserDelete.php';

// Clase UserService
class UserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }   

    // Elimina un usuario por su ID
    public function deleteUser($user_id) {
        return $this->userModel->deleteById($user_id);
    }
}   
?>
