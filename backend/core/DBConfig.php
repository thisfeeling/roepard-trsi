<?php

class DBConfig {
    private $db;

    // Obtiene la conexión a la base de datos
    public function getConnection() {
        if ($this->db) {
            return $this->db;
        }

        // Carga los credenciales de la base de datos
        $config = require __DIR__ . '/../config/db.php';

        // Crea la cadena de conexión a la base de datos
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";

        try {
            // Crea la conexión a la base de datos
            $this->db = new PDO($dsn, $config['user'], $config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->db;
        } catch (PDOException $e) {
            // Muestra el error de la base de datos
            die('Error de conexión: ' . $e->getMessage());
        }
    }
}
?>
