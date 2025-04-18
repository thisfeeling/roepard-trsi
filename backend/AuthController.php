<?php
// Incluir un archivo de conexion a la base de datos
require_once '../backend/DBConfig.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST['username']; // El valor puede ser email, username o phone
    $password = $_POST['password'];
    // Crear instancia de conexión
    $auth = new DBconfig();
    $db = $auth->getConnection();
    try {
        // Verificar si es un email
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            // Es un email
            $sql = "SELECT * FROM users WHERE email = :input AND password = :password";
        } 
        // Verificar si es un telefono
        elseif (is_numeric($input)) {
            // Es un numero de telefono
            $sql = "SELECT * FROM users WHERE phone = :input AND password = :password";
        } 
        else {
            // Es un username
            $sql = "SELECT * FROM users WHERE username = :input AND password = :password";
        }
        // Preparar y ejecutar la consulta
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':input', $input, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        // Obtener los datos
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            // Usuario encontrado, iniciar sesion
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role_id'] = $row['role_id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['phone'] = $row['phone'];
            $_SESSION['birthdate'] = $row['birthdate'];
            $_SESSION['profile_picture'] = $row['profile_picture'];
            $response = array(
                'status' => 'success',
                'message' => 'Login successful',
                'user_data' => $row
            );
        } else {
            // Usuario no encontrado o contraseña incorrecta
            $response = array(
                'status' => 'error',
                'message' => 'Incorrect Credentials.'
            );
        }
    } catch (PDOException $e) {
        $response = array('status' => 'error', 'message' => $e->getMessage());
    }

    // Devolver la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
