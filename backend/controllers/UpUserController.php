<?php
require_once __DIR__ . '/../../backend/core/DBConfig.php';

$auth = new DBconfig();
$db = $auth->getConnection();

// Obtener todos los datos del formulario excepto la imagen
$user_id = $_POST['user_id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$username = $_POST['username'];
$email = $_POST['email'];
$phone_prefix = $_POST['phone_prefix'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$current_password = $_POST['current_password'] ?? '';
$country = $_POST['country'];
$city = $_POST['city'];
$birthdate = $_POST['birthdate'];
$status_id = $_POST['status_id'];
$role_id = $_POST['role_id'];

// Concatenar el prefijo al número de teléfono
// Evitar duplicación del prefijo
if (str_starts_with($phone, $phone_prefix)) {
    $phone = substr($phone, strlen($phone_prefix));
}
$full_phone = $phone_prefix . $phone;


// Directorio y tipos permitidos
$upload_dir = __DIR__ . '/../../uploads/';
$allowed_types = ['image/jpeg', 'image/png', 'image/heic'];
$profile_picture = 'default-profile.png';  // Imagen por defecto

// Obtener la imagen actual del usuario
$sql_current = "SELECT profile_picture FROM users WHERE user_id = :user_id";
$stmt_current = $db->prepare($sql_current);
$stmt_current->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_current->execute();
$current = $stmt_current->fetch(PDO::FETCH_ASSOC);
if ($current && !empty($current['profile_picture'])) {
    $profile_picture = $current['profile_picture'];  // Mantener la imagen actual si no se sube una nueva
}

// Si se envía una nueva imagen
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
    // Eliminar la imagen anterior si existe
    if ($profile_picture !== 'default-profile.png' && file_exists($upload_dir . $profile_picture)) {
        unlink($upload_dir . $profile_picture);  // Eliminar la imagen vieja
    }

    $file_tmp = $_FILES['profile_picture']['tmp_name'];
    $file_type = mime_content_type($file_tmp);

    if (in_array($file_type, $allowed_types)) {
        $extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $new_filename = "user_" . $user_id . '.' . $extension;  // Usar el ID del usuario
        $destination = $upload_dir . $new_filename;

        if (move_uploaded_file($file_tmp, $destination)) {
            $profile_picture = $new_filename;  // Actualizar el nombre de la imagen en la base de datos
        } else {
            echo json_encode(['error' => 'Error al subir la imagen.']);
            exit;
        }
    } else {
        echo json_encode(['error' => 'Formato de imagen no permitido.']);
        exit;
    }
}

// Validación mejorada
$campos_obligatorios = [
    'user_id' => $user_id,
    'first_name' => $first_name,
    'last_name' => $last_name,
    'username' => $username,
    'email' => $email,
    'phone' => $phone,
    'phone_prefix' => $phone_prefix,
    'password' => $password,
    'current_password' => $current_password,
    'country' => $country,
    'city' => $city,
    'birthdate' => $birthdate,
    'status_id' => $status_id,
    'role_id' => $role_id
];

foreach ($campos_obligatorios as $campo => $valor) {
    if (!$valor) {
        echo json_encode(['error' => "El campo '$campo' es obligatorio."]);
        exit;
    }
}

// Validar que la contraseña actual sea correcta
$sql_check = "SELECT password FROM users WHERE user_id = :user_id";
$stmt_check = $db->prepare($sql_check);
$stmt_check->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_check->execute();
$user = $stmt_check->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['password'] !== $current_password) {
    echo json_encode(['error' => 'La contraseña actual es incorrecta.']);
    exit;
}

// Actualizar datos
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
$query->bindParam(':phone', $full_phone);
$query->bindParam(':password', $password);
$query->bindParam(':country', $country);
$query->bindParam(':city', $city);
$query->bindParam(':birthdate', $birthdate);
$query->bindParam(':status_id', $status_id);
$query->bindParam(':role_id', $role_id);
$query->bindParam(':user_id', $user_id);

if ($query->execute()) {
    echo json_encode(['success' => 'Usuario actualizado correctamente.']);
} else {
    echo json_encode(['error' => 'Error al actualizar el usuario.']);
}
?>
