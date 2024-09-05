<?php
require(__DIR__ . "/EnvManager.php");
class Database {
    /**
     * @var PDO Instance de PDO
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
     * @param int    $userId Id de l'utilisateur.
     * @param string $order  Contenu de la commande.
     * @param string $perso  Informations personnelles de l'utilisateur.
     *
     * @return bool True si l'insertion a réussi, sinon false.
     */
    public static function insertOrder(int $userId, string $order, string $perso): bool 
    {
        $currentDate = date('Y-m-d');
        $query = "INSERT INTO orders (user_id, content, perso, creation_date) VALUES (:user_id, :order, :perso, :creation_date)";
        try {
            $stmt = self::getInstance()->prepare($query);
            $stmt->bindParam(':user_id', $userId);
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
     * Méthode statique pour éditer une commande dans la base de données.
     *
     * @param int $orderId Id de la commande.
     * @param string $order Contenu de la commande.
     * @param string $perso Informations personnelles de l'utilisateur.
     *
     * @return bool True si l'insertion a réussi, sinon false.
     */
    public static function editOrder(int $orderId, string $order, string $perso): bool
    {
        $currentDate = date("Y-m-d");
        $query = "UPDATE orders SET content = :order, perso =:perso, modification_date = :modification_date WHERE id = :id";
        try {
            // Préparation de la requête
            $stmt = self::getInstance()->prepare($query);

            // Liaison des paramètres
            $stmt->bindParam(':order', $order);
            $stmt->bindParam(':perso', $perso);
            $stmt->bindParam(':modification_date', $currentDate);
            $stmt->bindParam(':id', $orderId);

            // Exécution de la requête et vérification du succès
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
            $stmt->bindParam(':creation_date', $currentDate);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [
                "error" => "Erreur lors de la récupération des commandes pour la date donnée : " . $e->getMessage()
            ];
        }
    }

    /**
     * Méthode pour récupérer une commande depuis la base de données avec son order id.
     *
     * @param int $orderId Id de la commande
     *
     * @return array Tableau contenant le détail de la commande ou un message d'erreur.
     */
    public static function getOneOrder(int $orderId): array
    {
        $currentDate = date("Y-m-d");
        $query = "SELECT * FROM orders WHERE id = :order_id";
        try {
            $stmt = self::getInstance()->prepare($query);
            $stmt->bindParam(':order_id', $orderId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [
                "error" => "Erreur lors de la récupération des commandes pour la date donnée : " . $e->getMessage()
            ];
        }
    }

    /**
     * Supprimer une commande
     *
     * @param integer $id Id de la commande
     *
     * @return boolean True si la suppression à réussie
     */
    public static function deleteOrder(int $id): bool
    {
        $query = "DELETE FROM orders WHERE id = :id";
        try {
            // Préparation de la requête
            $stmt = self::getInstance()->prepare($query);
            $stmt->bindParam(':id', $id);

            // Exécution de la requête et vérification du succès
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Méthode pour récupérer la liste des utilisateurs
     *
     * @param bool $isOrderByName Retourne les utilisateurs triés par noms
     *
     * @return array Tableau contenant la liste des utilisateurs
     */
    public static function getAllUsers(bool $isOrderByName = true): array
    {
        $query = "SELECT * FROM users" . ($isOrderByName ? " ORDER BY name" : "");
        try {
            $stmt = self::getInstance()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [
                "error" => "Erreur lors de la récupération de la liste des utilisateurs : " . $e->getMessage()
            ];
        }
    }
    
    /**
     * Ajouter un nouvel utilisateur
     *
     * @param string $name Nom de l'utilisateur
     *
     * @return boolean True si l'insertion a réussi
     */
    public static function insertUser(string $name): bool
    {
        $currentDate = date('Y-m-d');
        $query = "INSERT INTO users (name, creation_date, modification_date) VALUES (:name, :creation_date, :modification_date)";
        try {
            $stmt = self::getInstance()->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':creation_date', $currentDate);
            $stmt->bindParam(':modification_date', $currentDate);
            // Exécuter la requête et vérifier le succès
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Modifier le nom d'un utilisateur
     *
     * @param integer $id   Id de l'utilisateur
     * @param string  $name Nouveau nom de l'utilisateur
     *
     * @return boolean True si la modification a réussie
     */
    public static function editUser(int $id, string $name): bool
    {
        $currentDate = date("Y-m-d");
        $query = "UPDATE users SET name = :name, modification_date = :modification_date WHERE id = :id";
        try {
            // Préparation de la requête
            $stmt = self::getInstance()->prepare($query);
    
            // Liaison des paramètres
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':modification_date', $currentDate);
            $stmt->bindParam(':id', $id);
    
            // Exécution de la requête et vérification du succès
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Supprimer un utilisateur
     *
     * @param integer $id Id de l'utilisateur
     *
     * @return boolean True si la suppression à réussie
     */
    public static function deleteUser(int $id): bool
    {
        $query = "DELETE FROM users WHERE id = :id";
        try {
            // Préparation de la requête
            $stmt = self::getInstance()->prepare($query);
            $stmt->bindParam(':id', $id);
           
            // Exécution de la requête et vérification du succès
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Retrouvé un utilisateur avec son Id
     *
     * @param integer $id Id de l'utilisateur
     *
     * @return array|null L'utilisateur si il est trouvé
     */
    public static function getOneUser(int $id): ?array
    {
        $query = "SELECT * FROM users WHERE id= :id";
        try {
            // Préparation de la requête
            $stmt = self::getInstance()->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute(); // Exécution de la requête
            
            // Récupération du résultat
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner l'utilisateur ou null s'il n'est pas trouvé
            return ($user !== false ? $user : null);
        } catch (PDOException $e) {
            return [
                "error" => "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage()
            ];
        }
    }
}