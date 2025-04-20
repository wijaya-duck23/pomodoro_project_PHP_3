<?php

require_once 'core/Router.php';
require_once 'app/controllers/TimerController.php';
require_once 'app/controllers/SessionController.php';

// Initialize router
$router = new Router();

// Timer routes
$router->get('/', [new TimerController(), 'index']);
$router->post('/api/timer/start', [new TimerController(), 'startSession']);
$router->post('/api/timer/end', [new TimerController(), 'endSession']);
$router->get('/api/timer/stats', [new TimerController(), 'getStats']);

// Auth routes
$router->post('/api/auth/login', [new SessionController(), 'login']);
$router->post('/api/auth/register', [new SessionController(), 'register']);
$router->post('/api/auth/logout', [new SessionController(), 'logout']);
$router->get('/api/auth/check', [new SessionController(), 'checkAuth']);

// Dispatch the route
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Remove base path if necessary
$config = require 'config/config.php';
$basePath = parse_url($config['base_url'], PHP_URL_PATH);
if ($basePath && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// Ensure uri starts with /
if ($uri !== '/' && strlen($uri) > 0 && $uri[0] !== '/') {
    $uri = '/' . $uri;
}

// If uri is empty, set it to '/' for the home page
if (empty($uri)) {
    $uri = '/';
}

try {
    $controller = $router->route($uri, $method);
    
    if (is_array($controller)) {
        $controller[0]->{$controller[1]}();
    } else {
        call_user_func($controller);
    }
} catch (Exception $e) {
    // Handle 404 or other errors
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => $e->getMessage()]);
} 