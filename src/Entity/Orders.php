<?php

namespace App\Entity;
use App\Database\Database;
use PDO;
use PDOException;

class Orders
{
    private PDO $db;

    public function __construct()
    {
        // Initialisation de l'instance de la base de données
        $this->db = Database::getInstance();
    }


    /**
     * Retrouvé une commande avec son Id
     *
     * @param integer $id Id de la commande
     *
     * @return ?array La commande ?
     */
    public function find(int $id): ?array
    {
        $query = "SELECT * FROM orders WHERE id= :id";
        try {
            // Préparation de la requête
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Récupération du résultat
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner l'utilisateur ou null s'il n'est pas trouvé
            return ($order !== false ? $order : null);
        } catch (PDOException $e) {
            return [
                "error" => "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage()
            ];
        }
    }

    /**
     * Méthode pour insérer une commande dans la base de données.
     *
     * @param int $userId Id de l'utilisateur.
     * @param array $dishes Contenu de la commande.
     * @param string $perso Informations personnelles de l'utilisateur.
     *
     * @return bool True si l'insertion a réussi, sinon false.
     */
    public function insert(int $userId, array $dishes, string $perso): bool
    {
        $currentDate = date('Y-m-d');
        // Démarrer une transaction pour garantir que les deux inserts se font correctement
        $this->db->beginTransaction();

        try {
            // 1. Insérer dans la table `orders`
            $query = "
                INSERT INTO orders (user_id, perso, creation_date, modification_date)
                VALUES (:user_id, :perso, :creation_date, :modification_date)
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':perso', $perso);
            $stmt->bindParam(':creation_date', $currentDate);
            $stmt->bindParam(':modification_date', $currentDate);

            $stmt->execute(); // Exécuter la requête
            $orderId = $this->db->lastInsertId(); // Récupérer l'ID de la commande insérée

            // 2. Insérer les plats dans la table `order_dishes`
            foreach ($dishes as $dishId => $quantity) {
                if ($quantity > 0) { // Vérifier si la quantité est supérieure à 0
                    $query = "
                        INSERT INTO order_dishes (order_id, dish_id, quantity)
                        VALUES (:order_id, :dish_id, :quantity)
                    ";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $orderId);
                    $stmt->bindParam(':dish_id', $dishId);
                    $stmt->bindParam(':quantity', $quantity);

                    $stmt->execute(); // Exécuter l'insertion pour chaque plat
                }
            }

            // Si tout est OK, valider la transaction
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            // En cas d'erreur, annuler la transaction
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Méthode pour éditer une commande dans la base de données.
     *
     * @param int $orderId Id de la commande.
     * @param array $dishes Contenu de la commande.
     * @param string $perso Informations personnelles de l'utilisateur.
     *
     * @return bool True si l'édition a réussi, sinon false.
     */
    public function edit(int $orderId, array $dishes, string $perso): bool
    {
        $currentDate = date('Y-m-d');

        // Démarrer une transaction
        $this->db->beginTransaction();

        try {
            // 1. Mettre à jour la commande dans `orders`
            $query = "
                UPDATE orders 
                SET perso = :perso, modification_date = :modification_date 
                WHERE id = :id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':perso', $perso);
            $stmt->bindParam(':modification_date', $currentDate);
            $stmt->bindParam(':id', $orderId);
            $stmt->execute();

            // 2. Supprimer les anciens plats de la commande
            $query = "
                DELETE FROM order_dishes WHERE order_id = :order_id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':order_id', $orderId);
            $stmt->execute();

            // 3. Réinsérer les nouveaux plats
            foreach ($dishes as $dishId => $quantity) {
                if ($quantity > 0) { // Vérifier si la quantité est > 0
                    $query = "
                        INSERT INTO order_dishes (order_id, dish_id, quantity) 
                        VALUES (:order_id, :dish_id, :quantity)
                    ";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $orderId);
                    $stmt->bindParam(':dish_id', $dishId);
                    $stmt->bindParam(':quantity', $quantity);
                    $stmt->execute();
                }
            }

            // Valider la transaction
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $this->db->rollBack();
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
        $query = "
            SELECT o.id AS order_id, o.creation_date, o.perso, od.quantity, o.user_id, od.dish_id
            FROM orders o
            JOIN order_dishes od ON o.id = od.order_id
            WHERE o.creation_date = :date
            ORDER BY o.creation_date, o.id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':date', $currentDate);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Méthode pour récupérer les commandes depuis la base de données pour la date du jour.
     *
     * @return array Tableau contenant les commandes pour la date donnée ou un message d'erreur.
     */
    public function getTodayDishTotalQuantity(): array
    {
        $returnValue = [];

        $currentDate = date("Y-m-d");
        $query = "
            SELECT od.dish_id AS dish_id, SUM(od.quantity) AS total_quantity
            FROM order_dishes od
            JOIN orders o ON od.order_id = o.id
            WHERE o.creation_date = :date
            GROUP BY od.dish_id
            ORDER BY od.dish_id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':date', $currentDate);
        $stmt->execute();

        $resultArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($resultArray as $item) {
            $returnValue[$item['dish_id']] = (int)$item['total_quantity'];
        }

        return $returnValue;
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
        $query = "
            SELECT * FROM orders WHERE id = :order_id
        ";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [
                "error" => "Erreur lors de la récupération des commandes pour la date donnée : " . $e->getMessage()
            ];
        }
    }

    /**
     * Supprimer une commande et ses plats associés.
     *
     * @param int $id Id de la commande
     *
     * @return bool True si la suppression a réussi, sinon false.
     */
    public function delete(int $id): bool
    {
        // Démarrer une transaction
        $this->db->beginTransaction();

        try {
            // 2. Supprimer la commande elle-même
            $query = "
                DELETE FROM orders WHERE id = :id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // 1. Supprimer les plats associés à la commande
            $query = "
                DELETE FROM order_dishes WHERE order_id = :id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Valider la transaction
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $this->db->rollBack();
            return false;
        }
    }
}