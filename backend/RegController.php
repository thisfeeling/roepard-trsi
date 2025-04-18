<?php
// Incluir un archivo de conexion a la base de datos
require_once '../backend/DBConfig.php';
// Crear variable de sesión
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $birthdate = $_POST['birthdate'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Encriptar no se tiene en cuenta cuando se registra
    // $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Encriptar la contraseña
    // Crear instancia de conexión a la base de datos
    $auth = new DBconfig();
    $db = $auth->getConnection();
    try {
        // Verificar si el email o username ya existen
        $sql = "SELECT * FROM users WHERE email = :email OR username = :username OR phone = :phone";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->execute();
        $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existing_user) {
            $response = array(
                'status' => 'error',
                'message' => 'Email or username or phone already in use.'
            );
        } else {
            // Preparar la consulta SQL para insertar un nuevo usuario
            $sql = "INSERT INTO users (first_name, last_name, phone, country, city, birthdate, username, email, password) VALUES (:first_name, :last_name, :phone, :country, :city, :birthdate, :username, :email, :password)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':country', $country, PDO::PARAM_STR);
            $stmt->bindParam(':city', $city, PDO::PARAM_STR);
            $stmt->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            $response = array(
                'status' => 'success',
                'message' => 'User registered successfully.'
            );
        }
    } catch (PDOException $e) {
        $response = array(
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        );
    }
    // Retornar respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>