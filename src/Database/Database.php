<?php

namespace App\Database;

use PDO;
use PDOException;


class Database
{
    /**
     * @var ?PDO Instance de PDO
     */
    private static ?PDO $instance = null;

    /**
     * Constructeur privé pour empêcher l'instanciation directe de la classe.
     */
    private function __construct()
    {
    }


    /**
     * Méthode pour obtenir une instance unique de la classe Database.
     *
     * @return PDO Instance de la classe PDO.
     */
    public static function getInstance(): PDO
    {
        if (!self::$instance) {
            $config = require_once __DIR__.'/../../config/database.php';
            try {
                self::$instance = new PDO("mysql:host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['username'], $config['password']);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Ici il n'y a qu'une requête ajax qui utilise la connexion à la base de donnée
                die('Erreur de connexion à la base de données : ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}