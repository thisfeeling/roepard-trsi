<?php
// Creacion de una clase para conectar a la base de datos
class DBConfig {
    private $host = "roepard.ip-ddns.com:3306"; // Recordar cambiar puerto que se esta usando
    private $username = "trsi_user";
    private $password = "974120T";
    private $db_name = "trsi"; // Nombre de la base de datos
    // Metodo que retorna la conexion
    public function getConnection() {
        $conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
}
?>
