<?php
require_once __DIR__ . '/../../backend/core/DBConfig.php';

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

    header('Content-Type: application/json');
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