<?php

class Orders
{
    private PDO $db;

    public function __construct()
    {
        // Initialisation de l'instance de la base de données
        $this->db = Database::getInstance();
    }

    /**
     * Méthode pour insérer une commande dans la base de données.
     *
     * @param int    $userId Id de l'utilisateur.
     * @param string $order  Contenu de la commande.
     * @param string $perso  Informations personnelles de l'utilisateur.
     *
     * @return bool True si l'insertion a réussi, sinon false.
     */
    public function insertOrder(int $userId, string $order, string $perso): bool
    {
        $currentDate = date('Y-m-d');
        $query = "INSERT INTO orders (user_id, content, perso, creation_date) VALUES (:user_id, :order, :perso, :creation_date)";
        try {
            $stmt = $this->db->prepare($query);
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
     * @param int    $orderId Id de la commande.
     * @param string $order   Contenu de la commande.
     * @param string $perso   Informations personnelles de l'utilisateur.
     *
     * @return bool True si l'insertion a réussi, sinon false.
     */
    public function editOrder(int $orderId, string $order, string $perso): bool
    {
        $currentDate = date("Y-m-d");
        $query = "UPDATE orders SET content = :order, perso =:perso, modification_date = :modification_date WHERE id = :id";
        try {
            // Préparation de la requête
            $stmt = $this->db->prepare($query);

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
    public function getTodayOrders(): array
    {
        $currentDate = date("Y-m-d");
        $query = "SELECT * FROM orders WHERE creation_date = :creation_date";
        try {
            $stmt = $this->db->prepare($query);
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
    public function getOneOrder(int $orderId): array
    {
        $currentDate = date("Y-m-d");
        $query = "SELECT * FROM orders WHERE id = :order_id";
        try {
            $stmt = $this->db->prepare($query);
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
    public function deleteOrder(int $id): bool
    {
        $query = "DELETE FROM orders WHERE id = :id";
        try {
            // Préparation de la requête
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);

            // Exécution de la requête et vérification du succès
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}