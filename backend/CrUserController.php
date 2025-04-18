<?php
// Incluir un archivo de conexion a la base de datos
require_once '../backend/DBConfig.php';
// Crear instancia de conexión a la base de datos
$auth = new DBconfig();
$db = $auth->getConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que todos los campos estén presentes
    $fields = ['profile_picture', 'first_name', 'last_name', 'username', 'email', 'phone', 'password', 'country', 'city', 'birthdate','status_id', 'role_id'];
    foreach ($fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            echo json_encode(['success' => false, 'message' => "The $field field is required."]);
            exit;
        }
    }
    // Preparar la consulta SQL
    $sql = "INSERT INTO users (profile_picture, first_name, last_name, username, email, phone, password, country, city, birthdate, status_id, role_id)
            VALUES (:profile_picture, :first_name, :last_name, :username, :email, :phone, :password, :country, :city, :birthdate, :status_id, :role_id)";
    $stmt = $db->prepare($sql);
    try {
        $stmt->execute([
            ':profile_picture' => $_POST['profile_picture'],
            ':first_name' => $_POST['first_name'],
            ':last_name' => $_POST['last_name'],
            ':username' => $_POST['username'],
            ':email' => $_POST['email'],
            ':phone' => $_POST['phone'],
            ':password' => $_POST['password'],
            ':country' => $_POST['country'],
            ':city' => $_POST['city'],
            ':birthdate' => $_POST['birthdate'],
            ':status_id' => $_POST['status_id'],
            ':role_id' => $_POST['role_id']
        ]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
