<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function autoload($class)
{
    $directories = [
        __DIR__ . '/database/',
        __DIR__ . '/helpers/',
        __DIR__ . '/exceptions/',
        __DIR__ . '/models/',
        __DIR__ . '/controllers/',
    ];

    foreach ($directories as $directory) {
        $filePath = $directory . $class . '.php';
        if (file_exists($filePath)) {
            require_once $filePath;
            return;
        }
    }
}

spl_autoload_register('autoload');

// Load the Routes
$routes = require 'routes/web.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Folder name to path
$requestUri = str_replace('/project', '', $requestUri);

$requestMethod = $_SERVER['REQUEST_METHOD'];

$routeFound = false;

foreach ($routes as $route) {
    if ($route['uri'] === $requestUri && $route['method'] === $requestMethod) {
        $routeFound = true;
        $routeAction = $route['action'];

        list($controller, $action) = explode('@', $routeAction);

        // Load the Controller
        if (file_exists("controllers/{$controller}.php")) {
            require_once "controllers/{$controller}.php";
            $controllerInstance = new $controller();
            $controllerInstance->$action();
        } else {
            echo json_encode(['error' => 'Controller not found']);
        }
        break;
    }
}

if (!$routeFound) {
    // If not found the route
    http_response_code(404);
    echo json_encode(['error' => 'Route not found or incorrect HTTP method']);
}