<?php
require_once __DIR__ . '/../models/UserList.php';

class UserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function listUsers() {
        return $this->userModel->getAllUsers();
    }
}
