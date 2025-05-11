<?php
// Incluir un archivo de conexion a la base de datos
require_once __DIR__ . '/../../backend/core/DBConfig.php';
$auth = new DBconfig();
$db = $auth->getConnection();
// Cerrar sesion
session_start();
session_destroy();
header("Location: /../../trsi/index.php");
exit();
?>