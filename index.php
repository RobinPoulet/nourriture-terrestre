<?php
require __DIR__ . '/vendor/autoload.php';

use App\Core\Router;

ini_set('display_errors', 1);
error_reporting(E_ALL);
// Initialiser le Router et gérer la requête
$router = new Router();
$router->handleRequest();