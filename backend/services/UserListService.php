<?php
require_once __DIR__ . '/../models/UserList.php';

class UserListService {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../core/DBConfig.php';
        $dbConfig = new DBConfig();
        $this->db = $dbConfig->getConnection();
    }

    public function listUsers() {
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
