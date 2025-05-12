<?php
// Incluye el controlador encargado de la lógica para eliminar usuarios
require_once __DIR__ . '/../controllers/DelUserController.php';

// Crea una instancia del controlador
$controller = new DelUserController();

// Llama al método que maneja la petición HTTP (POST) y responde en JSON
$controller->handleRequest();

// Nota: No pongas nada después de esto para evitar romper el formato JSON