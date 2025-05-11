<?php
require_once __DIR__ . '/../../backend/core/DBConfig.php';

$auth = new DBconfig();
$db = $auth->getConnection();

header('Content-Type: application/json'); // <- IMPORTANTE

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
INNER JOIN roles ON users.role_id = roles.role_id";

$query = $db->prepare($sql);
$query->execute();

$data = [];

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $statusClass = ($row['status_id'] == 1) ? 'bg-danger' : 'bg-success';

    $data[] = [
        "user_id" => $row['user_id'],
        "profile_picture" => htmlspecialchars($row['profile_picture']),
        "username" => $row['username'],
        "email" => $row['email'],
        "phone" => $row['phone'],
        "country" => $row['country'],
        "city" => $row['city'],
        "first_name" => $row['first_name'],
        "last_name" => $row['last_name'],
        "status_name" => $row['status_id'],
        "role_name" => $row['rol'],
        "created_at" => $row['created_at'],
        "updated_at" => $row['updated_at'],
        "birthdate" => $row['birthdate'],
    ];
}

// Verifica si los datos están vacíos antes de enviarlos
if (empty($data)) {
    echo json_encode(["data" => []]); // Si no hay datos, enviar un array vacío
} else {
    echo json_encode(["data" => $data]); // Enviar los datos correctamente formateados
}
?>
