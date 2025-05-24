<?php
require __DIR__ . '/vendor/autoload.php';

use App\Core\ErrorHandler;
use App\Core\Router;

// Détection de l'environnement
$isDebugUrl = (isset($_GET['debug']) && $_GET['debug'] === '1');
$isDev = ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_ADDR'] === '127.0.0.1');
$isDebug = ($isDev || $isDebugUrl);

if ($isDebug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

$handler = new ErrorHandler($isDebug);

try {
    $router = new Router();
    $router->handleRequest();
} catch (\Throwable $e) {
    $handler->handle($e);
}
