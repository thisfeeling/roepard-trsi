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
    $people_count_5min = [];
    $battery_percentage = [];
    $power_source = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = date('H:i:s', strtotime($row['timestamp']));
        $fps[] = (float)$row['fps'];
        $people_count_5min[] = (int)$row['people_count_5min'];
        $battery_percentage[] = (float)$row['battery_percentage']; 
        $power_source[] = $row['power_source']; // string
    }

    header('Content-Type: application/json');
    echo json_encode([
        "labels" => $labels,
        "fps" => $fps,
        "people_count_5min" => $people_count_5min,
        "battery_percentage" => $battery_percentage,
        "power_source" => $power_source
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>