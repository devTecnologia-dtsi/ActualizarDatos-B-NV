<?php
    require_once 'controllers/UserController.php';
    require_once 'config/database.php';
    require_once 'middleware/authMiddleware.php';

    // If the method is OPTIONS, respond with 200 OK
    $roleController = new UserController();

    // Get the method and data from the request
    $method = $_SERVER['REQUEST_METHOD'];
    $data = json_decode(file_get_contents("php://input"), true);
    // Send the method and data to the controller
    $roleController->handleRequest($method, $vars, $data);
?>