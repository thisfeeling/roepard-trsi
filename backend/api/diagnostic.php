<?php
require_once __DIR__ . '/../core/DBConfig.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $response = [];

    if ($action === 'db') {
        // Verificar BD
        $auth = new DBConfig();
        $db = $auth->getConnection();
        try {
            // Prueba de conexion
            $stmt = $db->query("SELECT 1");
            $response['db_status'] = $stmt ? true : false;

            // Permisos
            $response['perm_select'] = false;
            $response['perm_insert'] = false;
            $response['perm_update'] = false;
            $response['perm_delete'] = false;

            // SELECT
            try {
                $stmt = $db->query("SELECT * FROM users LIMIT 1");
                $response['perm_select'] = true;
            } catch (PDOException $e) {
                error_log("Error SELECT: " . $e->getMessage());
            }

            // INSERT
            try {
                $stmt = $db->prepare("INSERT INTO users (username, password, role_id) VALUES (?, ?, ?)");
                $response['perm_insert'] = true;
            } catch (PDOException $e) {
                error_log("Error INSERT: " . $e->getMessage());
            }

            // UPDATE
            try {
                $stmt = $db->prepare("UPDATE users SET username = ? WHERE user_id = ?");
                $response['perm_update'] = true;
            } catch (PDOException $e) {
                error_log("Error UPDATE: " . $e->getMessage());
            }

            // DELETE
            try {
                $stmt = $db->prepare("DELETE FROM users WHERE user_id = ?");
                $response['perm_delete'] = true;
            } catch (PDOException $e) {
                error_log("Error DELETE: " . $e->getMessage());
            }

        } catch (PDOException $e) {
            $response['db_status'] = false;
            error_log("Error de conexión: " . $e->getMessage());
        }
    } elseif ($action === 'jetson') {
        // Verificar Jetson Nano
        $jetson_ip = '192.168.1.100';
        $ping_result = shell_exec("ping -c 1 -W 1 $jetson_ip");
        $response['jetson_status'] = strpos($ping_result, '1 received') !== false;
    }
    
    echo json_encode($response);
    exit;
}

echo json_encode(['error' => 'Método no permitido']);
?>