<?php
require_once __DIR__ . '/../core/DBConfig.php';

class User {
    private $db;

    public function __construct() {
        $auth = new DBConfig();
        $this->db = $auth->getConnection();
    }

    public function deleteById($user_id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }
}
?>
