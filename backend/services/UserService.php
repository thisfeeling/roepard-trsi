<?php
require_once __DIR__ . '/../models/User.php';

class UserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function deleteUser($user_id) {
        return $this->userModel->deleteById($user_id);
    }
}