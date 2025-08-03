<?php
// Envía la respuesta en formato JSON
header('Content-Type: application/json');
// Requiere el conexion a la base de datos
require_once __DIR__ . '/../core/DBConfig.php';

// Requiere el middleware de auth y status
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../middleware/status.php';

Auth::checkAnyRole([1, 2, 3]);
Status::checkStatus(1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Solo aceptar GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido'
    ]);
    exit;
}

$auth = new DBConfig();
$db = $auth->getConnection();

try {
    // Obtener los últimos 50 datos ordenados por timestamp
    $stmt = $db->prepare("SELECT * FROM realtime_data ORDER BY data_id ASC LIMIT 50");
    $stmt->execute();

    $labels = [];
    $fps = [];
    $panel_voltage = [];
    $panel_current = [];
    $panel_power = [];
    $solar_radiation = [];
    $battery_percentage = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = date('H:i:s', strtotime($row['timestamp']));
        $fps[] = (float)$row['fps'];
        $panel_voltage[] = (float)$row['panel_voltage'];
        $panel_current[] = (float)$row['panel_current'];
        $panel_power[] = (float)$row['panel_power'];
        $solar_radiation[] = (float)$row['solar_radiation'];
        $battery_percentage[] = (float)$row['battery_percentage'];
    }

    echo json_encode([
        "labels" => $labels,
        "fps" => $fps,
        "panel_voltage" => $panel_voltage,
        "panel_current" => $panel_current,
        "panel_power" => $panel_power,
        "solar_radiation" => $solar_radiation,
        "battery_percentage" => $battery_percentage,
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>