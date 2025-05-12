<?php
require_once __DIR__ . '/../core/DBConfig.php';

class User {
    private $db;

    public function __construct() {
        $auth = new DBConfig();
        $this->db = $auth->getConnection();
    }

    public function getAllUsers() {
        $stmt = $this->db->query("
            SELECT 
                u.user_id,
                u.username,
                u.email,
                u.phone,
                u.country,
                u.city,
                u.first_name,
                u.last_name,
                u.status_id,
                r.role_id,
                u.created_at,
                u.updated_at,
                u.birthdate,
                u.profile_picture
            FROM users u
            JOIN roles r ON u.role_id = r.role_id
            ORDER BY u.user_id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
