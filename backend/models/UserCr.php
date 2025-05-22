<?php
// Requiere el conexion a la base de datos
require_once __DIR__ . '/../core/DBConfig.php';

// Clase UserCr
class User {
    private $db;

    // Crea una nueva instancia
    public function __construct() {
        $dbConfig = new DBConfig();
        $this->db = $dbConfig->getConnection();
    }

    // Crea un nuevo usuario
    public function create($userData) {
        try {
            $sql = "INSERT INTO users 
                (first_name, last_name, username, email, phone, password, country, city, birthdate, status_id, role_id, profile_picture)
                VALUES
                (:first_name, :last_name, :username, :email, :phone, :password, :country, :city, :birthdate, :status_id, :role_id, :profile_picture)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':first_name' => $userData['first_name'],
                ':last_name' => $userData['last_name'],
                ':username' => $userData['username'],
                ':email' => $userData['email'],
                ':phone' => $userData['phone'],
                ':password' => $userData['password'],
                ':country' => $userData['country'],
                ':city' => $userData['city'],
                ':birthdate' => $userData['birthdate'],
                ':status_id' => $userData['status_id'],
                ':role_id' => $userData['role_id'],
                ':profile_picture' => $userData['profile_picture']
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error al crear usuario: " . $e->getMessage());
        }
    }
}
?>