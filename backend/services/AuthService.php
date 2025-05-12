<?php
require_once __DIR__ . '/../models/UserAuth.php';

class AuthService {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function authenticate($input, $password) {
        $user = $this->userModel->findByCredentials($input);
        
        if (!$user || !password_verify($password, $user['password'])) {
            return [
                'status' => 'error',
                'message' => 'Credenciales incorrectas'
            ];
        }

        // Iniciar sesión
        session_start();
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['birthdate'] = $user['birthdate'];
        $_SESSION['profile_picture'] = $user['profile_picture'];

        return [
            'status' => 'success',
            'message' => 'Login exitoso',
            'user_data' => $user
        ];
    }
}
?>