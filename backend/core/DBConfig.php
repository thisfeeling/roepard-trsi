<?php
class DBConfig {
    private $db;

    public function getConnection() {
        if ($this->db) {
            return $this->db;
        }

        // Carga la configuración desde config/db.php
        $config = require __DIR__ . '/../config/db.php';

        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";

        try {
            $this->db = new PDO($dsn, $config['user'], $config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->db;
        } catch (PDOException $e) {
            // Puedes manejar el error como prefieras
            die('Error de conexión: ' . $e->getMessage());
        }
    }
}
?>
