<?php
require_once __DIR__ . '/../models/UserUpdate.php';
require_once __DIR__ . '/../models/UserDetails.php';

class UserService {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserDetails();
    }

    public function getUserById($user_id) {
        return $this->userModel->findById($user_id);
    }
}
?>