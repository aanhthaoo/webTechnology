<?php
session_start();

// Error reporting for development (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload configuration and models
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/config/Database.php';

// Load routes
$router = require_once __DIR__ . '/config/routes.php';

// Get request URI and method
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remove base path if running in subdirectory
$basePath = '/Website_Quan_ly_khoa_hoc_online';
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}

// Remove query string from URI
$requestUri = strtok($requestUri, '?');

// Ensure URI starts with /
if (empty($requestUri) || $requestUri === '') {
    $requestUri = '/';
}

try {
    // Dispatch the route
    $router->dispatch($requestUri, $requestMethod);
} catch (Exception $e) {
    // Handle errors
    http_response_code(500);
    
    // In production, log the error and show a generic error page
    if (defined('DEBUG') && DEBUG) {
        echo "<h1>Error 500 - Internal Server Error</h1>";
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        // Show generic error page
        $errorFile = __DIR__ . '/views/errors/500.php';
        if (file_exists($errorFile)) {
            include $errorFile;
        } else {
            echo "<h1>500 - Internal Server Error</h1>";
            echo "<p>Something went wrong. Please try again later.</p>";
        }
    }
}
?>