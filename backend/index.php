<?php

require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/routes/web.php';

$router->handleRequest();

// ejemplo de depuracion en index.php
echo "Ruta solicitada: " . $_SERVER['REQUEST_URI'];
?>