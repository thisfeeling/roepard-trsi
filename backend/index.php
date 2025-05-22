<?php
// Requiere el router y las rutas
// NO se utilizo en el proyecto.
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/routes/web.php';

$router->handleRequest();

?>