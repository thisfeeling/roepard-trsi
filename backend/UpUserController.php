<?php
// Incluir un archivo de conexion a la base de datos
require_once '../backend/DBConfig.php';
// Crear instancia de conexión a la base de datos
$auth = new DBconfig();
$db = $auth->getConnection();
// Obtener datos del formulario
$user_id = $_POST['user_id'];
$profile_picture = $_POST['profile_picture'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$country = $_POST['country'];
$city = $_POST['city'];
$birthdate = $_POST['birthdate'];
$status_id = $_POST['status_id'];
$role_id = $_POST['role_id'];
// Mostrar los datos recibidos para depuración
// Permanece desactivado por que no permite la funcion del controlador
/* echo json_encode(['debug' => $_POST]);
exit; */
// Validar que todos los datos requeridos estén presentes
if (!$user_id|| !$profile_picture || !$first_name || !$last_name || !$username || !$email || !$phone || !$password || !$country || !$city || !$birthdate || !$status_id || !$role_id ) {
    echo json_encode(['error' => 'All fields are required.']);
    exit;
}
// Actualizar datos del usuario en la base de datos
$sql = "UPDATE users SET 
            profile_picture = :profile_picture,
            first_name = :first_name, 
            last_name = :last_name,
            username = :username, 
            email = :email, 
            phone = :phone,
            password = :password,
            country = :country,
            city = :city,
            birthdate = :birthdate,
            status_id = :status_id, 
            role_id = :role_id
        WHERE user_id = :user_id";
$query = $db->prepare($sql);
$query->bindParam(':profile_picture', $profile_picture);
$query->bindParam(':first_name', $first_name);
$query->bindParam(':last_name', $last_name);
$query->bindParam(':username', $username);
$query->bindParam(':email', $email);
$query->bindParam(':phone', $phone);
$query->bindParam(':password', $password);
$query->bindParam(':country', $country);
$query->bindParam(':city', $city);
$query->bindParam(':birthdate', $birthdate);
$query->bindParam(':status_id', $status_id);
$query->bindParam(':role_id', $role_id);
$query->bindParam(':user_id', $user_id);
if ($query->execute()) {
    echo json_encode(['success' => 'User updated successfully.']);
} else {
    echo json_encode(['error' => 'Error updating user.']);
}
?>