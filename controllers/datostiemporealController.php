<?php
// Requiere el conexion a la base de datos
require_once __DIR__ . '/../core/DBConfig.php';

// Crea una nueva instancia
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
    $jetson_voltage = [];
    $jetson_current = [];
    $jetson_power = [];
    $solar_radiation = [];
    $people_count_5min = [];
    $battery_percentage = [];
    $power_source = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = date('H:i:s', strtotime($row['timestamp']));
        $fps[] = (float)$row['fps'];
        $panel_voltage[] = (float)$row['panel_voltage'];
        $panel_current[] = (float)$row['panel_current'];
        $panel_power[] = (float)$row['panel_power'];
        $jetson_voltage[] = (float)$row['jetson_voltage'];
        $jetson_current[] = (float)$row['jetson_current'];
        $jetson_power[] = (float)$row['jetson_power'];
        $solar_radiation[] = (float)$row['solar_radiation'];
        $people_count_5min[] = (int)$row['people_count_5min'];
        $battery_percentage[] = (float)$row['battery_percentage']; 
        $power_source[] = $row['power_source']; // string
    }

    header('Content-Type: application/json');
    echo json_encode([
        "labels" => $labels,
        "fps" => $fps,
        "panel_voltage" => $panel_voltage,
        "panel_current" => $panel_current,
        "panel_power" => $panel_power,
        "jetson_voltage" => $jetson_voltage,
        "jetson_current" => $jetson_current,
        "jetson_power" => $jetson_power,
        "solar_radiation" => $solar_radiation,
        "people_count_5min" => $people_count_5min,
        "battery_percentage" => $battery_percentage,
        "power_source" => $power_source
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>