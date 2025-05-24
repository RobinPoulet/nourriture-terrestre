<?php
use Dotenv\Dotenv;

// Fichier de configuration de la base de données

$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

define('BASE_PATH', dirname(__DIR__));  // Définit la racine du projet
define('APP_ENV', $_ENV['APP_ENV']);
define('BASE_URL', $_ENV['BASE_URL']);
define('COMPLETE_URL', $_ENV['COMPLETE_URL']);
const PREFIX = (APP_ENV === 'development' ? '/nourriture-terrestre' : '');
