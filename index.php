<?php
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: GET, PATCH, POST, PUT, DELETE, OPTIONS");
//header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');
require 'vendor/autoload.php';
require 'middleware/authMiddleware.php';

// If the method is OPTIONS, respond with 200 OK
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// If the method is POST, PUT or DELETE, check if the content type is application/json
try {
    $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
        // Example routes for different methods, routes, and items
        $r->addRoute('GET', '/api/user/id/{id:\d+}', 'consult_user');
    });

    // Get route and method information
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    // Delete query string from URI
    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }

    $uri = rawurldecode($uri);
    // Send route and method information to the dispatcher
    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    // Switch statement to handle route information
    switch ($routeInfo[0]) {
        // If the route is not found
        case FastRoute\Dispatcher::NOT_FOUND:
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(["message" => "Route not found"]);
            break;
        // If the route is found but the method is not allowed
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            header('Content-Type: application/json');
            http_response_code(405);
            echo json_encode(["message" => "Disallowed method. Allowed methods: " . implode(', ', $allowedMethods)]);
            break;
        // If the route is found
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];

            if ($handler == 'consult_user') {
                require 'routes/user.php';
            }
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Internal Server Error", "error" => $e->getMessage()]);
}
