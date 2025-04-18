<?php
// Incluir un archivo de conexion a la base de datos
require_once '../backend/DBConfig.php';
// Crear instancia de conexión a la base de datos
$auth = new DBconfig();
$db = $auth->getConnection();
// Obtener el ID del usuario enviado desde el cliente
$user_id = $_POST['user_id'];
// Validar que el ID del usuario no esté vacío
if (!$user_id) {
    echo json_encode(['error' => 'El ID del usuario es obligatorio.']);
    exit;
}
// Eliminar el usuario de la base de datos
$sql = "DELETE FROM users WHERE user_id = :user_id";
$query = $db->prepare($sql);
$query->bindParam(':user_id', $user_id);
if ($query->execute()) {
    echo json_encode(['success' => 'Usuario eliminado correctamente.']);
} else {
    echo json_encode(['error' => 'Error al eliminar el usuario.']);
}
?>
