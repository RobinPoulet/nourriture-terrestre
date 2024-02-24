<?php
require(__DIR__ . "/EnvManager.php");
class Database {
    private static $instance;

    
    /**
     * Constructeur privé pour empêcher l'instanciation directe de la classe.
     */
    private function __construct() {}

    
    /**
     * Méthode pour obtenir une instance unique de la classe Database.
     *
     * @return PDO Instance de la classe PDO.
     */
    public static function getInstance(): PDO 
    {
        if (!self::$instance) {
            $envFilePath =__DIR__ . "/../.env";
            $envManager = EnvManager::getInstance($envFilePath);

            $dbHost = $envManager->getEnvVariable('DB_HOST');
            $dbUser = $envManager->getEnvVariable('DB_USER');
            $dbPass = $envManager->getEnvVariable('DB_PASS');
            $dbName = $envManager->getEnvVariable('DB_NAME');

            try {
                self::$instance = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUser, $dbPass);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Erreur de connexion à la base de données : ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
    
    /**
     * Méthode statique pour insérer une commande dans la base de données.
     *
     * @param string $user Nom de l'utilisateur.
     * @param string $order Contenu de la commande.
     * @param string $perso Informations personnelles de l'utilisateur.
     *
     * @return bool True si l'insertion a réussi, sinon false.
     */
    public static function insertOrder($user, $order, $perso) {
        $currentDate = date('Y-m-d');
        $query = "INSERT INTO orders (name, content, perso, creation_date) VALUES (:name, :order, :perso, :creation_date)";
        try {
            $stmt = self::getInstance()->prepare($query);
            $stmt->bindParam(':name', $user);
            $stmt->bindParam(':order', $order);
            $stmt->bindParam(':perso', $perso);
            $stmt->bindParam(':creation_date', $currentDate);
            // Exécuter la requête et vérifier le succès
            return $stmt->execute();
        } catch (PDOException $e) {
            die('Erreur lors de l\'insertion en base de données : ' . $e->getMessage());
        }
    }
    
    
    /**
     * Méthode pour récupérer les commandes depuis la base de données pour une date donnée.
     *
     * @param string $creation_date Date de création des commandes.
     *
     * @return array Tableau contenant les commandes pour la date donnée.
     */
    public static function getOrdersByCreationDate($creation_date): array 
    {
        $query = "SELECT * FROM orders WHERE creation_date = :creation_date";
        try {
            $stmt = self::getInstance()->prepare($query);
            $stmt->bindParam(':creation_date', $creation_date);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Erreur lors de la récupération des commandes pour la date donnée : ' . $e->getMessage());
        }
    }
}