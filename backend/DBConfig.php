<?php
// Creación de una clase para conectar a la base de datos
class DBConfig {
    private $host = "localhost"; // Host de la base de datos
    private $port = "3306"; // Puerto de la base de datos
    private $username = "trsi_user"; 
    private $password = "xJgt1bm_m!j8Ys5r";
    private $db_name = "trsi"; // Nombre de la base de datos

    // Método que retorna la conexión
    public function getConnection() {
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}";
        $conn = new PDO($dsn, $this->username, $this->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
}
?>
