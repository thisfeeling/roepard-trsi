<?php
// Clase para conectar a la base de datos MySQL usando PDO
class DBConfig {
    private $host = "localhost";
    private $port = "3306";
    private $username = "trsi_user";
    private $password = "xJgt1bm_m!j8Ys5r";
    private $db_name = "trsi";
    private static $instance = null;

    /**
     * Retorna una instancia PDO para la conexión a la base de datos.
     * Usa singleton para evitar múltiples conexiones.
     */
    public function getConnection() {
        if (self::$instance === null) {
            try {
                $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";
                self::$instance = new PDO($dsn, $this->username, $this->password);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                error_log("Error de conexión a la base de datos: " . $e->getMessage());
                die("No se pudo conectar a la base de datos.");
            }
        }
        return self::$instance;
    }
}
?>
