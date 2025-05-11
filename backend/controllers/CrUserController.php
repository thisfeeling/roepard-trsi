<?php
require_once __DIR__ . '/../../backend/core/DBConfig.php';

$auth = new DBconfig();
$db = $auth->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que todos los campos estén presentes
    $fields = ['first_name', 'last_name', 'username', 'email', 'phone', 'phone_prefix', 'password', 'country', 'city', 'birthdate','status_id', 'role_id'];
    foreach ($fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            echo json_encode(['success' => false, 'message' => "El campo $field es obligatorio."]);
            exit;
        }
    }

    // ¡AQUÍ! Asigna las variables antes de usarlas
    $phone_prefix = $_POST['phone_prefix'];
    $phone = $_POST['phone'];
    $full_phone = $phone_prefix . $phone;

    // Directorio y tipos permitidos
    $upload_dir = __DIR__ . '/../../uploads/';
    $allowed_types = ['image/jpeg', 'image/png', 'image/heic'];
    $profile_picture = 'default-profile.png';  // Imagen por defecto

    // Si se envía una imagen
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_type = mime_content_type($file_tmp);

        if (in_array($file_type, $allowed_types)) {
            $extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $unique_name = uniqid('user_', true) . '.' . $extension;
            $destination = $upload_dir . $unique_name;

            if (move_uploaded_file($file_tmp, $destination)) {
                $profile_picture = $unique_name;
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al subir la imagen.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Formato de imagen no permitido.']);
            exit;
        }
    }

    // Insertar datos del usuario
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (profile_picture, first_name, last_name, username, email, phone, password, country, city, birthdate, status_id, role_id)
            VALUES (:profile_picture, :first_name, :last_name, :username, :email, :phone, :password, :country, :city, :birthdate, :status_id, :role_id)";
    
    $stmt = $db->prepare($sql);
    
    try {
        $stmt->execute([
            ':profile_picture' => $profile_picture,
            ':first_name' => $_POST['first_name'],
            ':last_name' => $_POST['last_name'],
            ':username' => $_POST['username'],
            ':email' => $_POST['email'],
            ':phone' => $full_phone,
            ':password' => $hashed_password,
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
