<?php

// Clase status del middleware
class Status
{
    private static $db;

    // Método para obtener la conexión a la base de datos
    private static function getDB()
    {
        // Iniciar sesión solo si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!self::$db) {
            require_once __DIR__ . '/../core/DBConfig.php';
            $auth = new DBConfig();
            self::$db = $auth->getConnection();
        }
        return self::$db;
    }

    // Método para verificar si el usuario está autenticado
    public static function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autorizado']);
            exit();
        }
        return $_SESSION['user_id'];
    }

    // Método para verificar si el usuario tiene el rol requerido
    public static function checkStatus($required_status_id)
    {
        $user_id = self::checkAuth();

        $db = self::getDB();
        $stmt = $db->prepare("SELECT status_id FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['status_id'] != $required_status_id) {
            http_response_code(401);
            echo json_encode(['error' => 'No tienes permisos, usuario deshabilitado']);
            exit();
        }
    }

    // Método para verificar si el usuario tiene al menos uno de los roles permitidos
    public static function checkAnyStatus($allowed_statuses)
    {
        $user_id = self::checkAuth();

        $db = self::getDB();
        $stmt = $db->prepare("SELECT status_id FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !in_array($user['status_id'], $allowed_statuses)) {
            http_response_code(401);
            echo json_encode(['error' => 'No tienes permisos, usuario deshabilitado']);
            exit();
        }
    }
}