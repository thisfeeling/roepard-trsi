<?php
require_once __DIR__ . '/../../backend/core/DBConfig.php';

$auth = new DBconfig();
$db = $auth->getConnection();

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $sql = "SELECT 
                users.user_id,
                users.profile_picture,
                users.first_name,
                users.last_name,
                users.email,
                users.role_id, 
                users.username,
                users.phone,
                users.password,
                users.country,
                users.city,
                users.status_id,
                users.birthdate,
                users.created_at,
                users.updated_at,
                roles.role_name AS rol 
            FROM users 
            INNER JOIN roles ON users.role_id = roles.role_id
            WHERE users.user_id = :user_id";

    $query = $db->prepare($sql);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();

    if ($query->rowCount() > 0) {
        $user = $query->fetch(PDO::FETCH_ASSOC);
        
        // Verifica si la imagen de perfil es válida, si no, asigna la imagen por defecto
        if (empty($user['profile_picture'])) {
            $user['profile_picture'] = 'default-profile.png';
        }

        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'Usuario no encontrado']);
    }
} else {
    echo json_encode(['error' => 'ID de usuario no proporcionado']);
}
?>
