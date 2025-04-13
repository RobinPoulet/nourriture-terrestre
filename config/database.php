<?php

use Dotenv\Dotenv;

// Fichier de configuration de la base de données

$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

return [
    'host' => $_ENV['DB_HOST'],
    'dbname' => $_ENV['DB_NAME'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
];
