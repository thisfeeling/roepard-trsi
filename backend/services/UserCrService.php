<?php
require_once __DIR__ . '/../models/UserCr.php';

class UserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function createUser($userData) {
        // Validaciones básicas
        foreach (['first_name', 'last_name', 'username', 'email', 'phone', 'password', 'country', 'city', 'birthdate', 'status_id', 'role_id'] as $field) {
            if (empty($userData[$field])) {
                throw new Exception("El campo '$field' es obligatorio.");
            }
        }

        // Hash de la contraseña
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);

        // Crear usuario
        $result = $this->userModel->create($userData);

        if (!$result) {
            throw new Exception("Error al crear el usuario.");
        }

        return [
            'status' => 'success',
            'message' => 'Usuario creado correctamente.'
        ];
    }
}
?>