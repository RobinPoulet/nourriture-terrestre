<?php
require(__DIR__ . "/EnvManager.php");
class Database {
    /**
     * Instance de PDO
     *
     * @var PDO
     */
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
            $envFilePath = __DIR__ . "/../.env";
            $envManager = EnvManager::getInstance($envFilePath);
            $envVariables = $envManager->getAllEnvVariables();

            try {
                self::$instance = new PDO("mysql:host=" . $envVariables["DB_HOST"] . ";dbname=" . $envVariables["DB_NAME"], $envVariables["DB_USER"], $envVariables["DB_PASS"]);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Ici il n'y a qu'une requête ajax qui utilise la connexion à la base de donnée
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
            return false;
        }
    }
    
    
    /**
     * Méthode pour récupérer les commandes depuis la base de données pour la date du jour.
     *
     * @return array Tableau contenant les commandes pour la date donnée ou un message d'erreur.
     */
    public static function getTodayOrders(): array 
    {
        $currentDate = date("Y-m-d");
        $query = "SELECT * FROM orders WHERE creation_date = :creation_date";
        try {
            $stmt = self::getInstance()->prepare($query);
            $stmt->bindParam(':creation_date', $creation_date);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [
                "error" => "Erreur lors de la récupération des commandes pour la date donnée : " . $e->getMessage()
            ];
        }
    }
}