<?php

// Definición de controladores
$router = new Router(); // Asegúrate de tener una clase Router definida

// Definición de rutas


// Manejo de rutas no encontradas
$router->setNotFoundHandler(function() {
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada']);
});

?>
