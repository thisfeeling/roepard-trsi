<?php
require_once __DIR__ . '/../core/DBConfig.php';

class Diagnostic {
    private $db;

    public function __construct() {
        $auth = new DBConfig();
        $this->db = $auth->getConnection();
    }

    public function checkConnection() {
        try {
            $stmt = $this->db->query("SELECT 1");
            return $stmt ? true : false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function checkPermissions() {
        $perms = [
            'select' => false,
            'insert' => false,
            'update' => false,
            'delete' => false
        ];
        // SELECT
        try {
            $this->db->query("SELECT * FROM users LIMIT 1");
            $perms['select'] = true;
        } catch (PDOException $e) {}
        // INSERT (en tabla temporal)
        try {
            $this->db->exec("CREATE TEMPORARY TABLE IF NOT EXISTS temp_test (id INT)");
            $this->db->exec("INSERT INTO temp_test (id) VALUES (1)");
            $perms['insert'] = true;
        } catch (PDOException $e) {}
        // UPDATE
        try {
            $this->db->exec("UPDATE temp_test SET id = 2 WHERE id = 1");
            $perms['update'] = true;
        } catch (PDOException $e) {}
        // DELETE
        try {
            $this->db->exec("DELETE FROM temp_test WHERE id = 2");
            $perms['delete'] = true;
        } catch (PDOException $e) {}
        return $perms;
    }
}