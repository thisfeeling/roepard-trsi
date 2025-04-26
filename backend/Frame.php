<?php
// Incluir tu archivo de conexión
require_once '../backend/DBConfig.php';

// Crear instancia y obtener conexión
$auth = new DBConfig();
$db = $auth->getConnection();

try {
    // Consulta para obtener los datos de usuarios registrados por fecha
    $stmt = $db->prepare("SELECT DATE(created_at) as fecha, COUNT(*) as cantidad FROM users GROUP BY fecha ORDER BY fecha ASC");
    $stmt->execute();

    $labels = [];
    $data = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $row['fecha'];
        $data[] = (int)$row['cantidad'];
    }

    // Retornar como JSON
    header('Content-Type: application/json');
    echo json_encode([
        "labels" => $labels,
        "data" => $data
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
