<?php

namespace App\Entity;
use App\Database\Database;
use PDO;
use PDOException;

class Users
{
    private PDO $db;

    public function __construct()
    {
        // Initialisation de l'instance de la base de données
        $this->db = Database::getInstance();
    }

    /**
     * Retrouvé un utilisateur avec son Id
     *
     * @param integer $id Id de l'utilisateur
     *
     * @return array|null L'utilisateur si il est trouvé
     */
    public function find(int $id): ?array
    {
        $query = "SELECT * FROM users WHERE id= :id";
        try {
            // Préparation de la requête
            $stmt = $this->db->prepare($query);
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

    /**
     * Méthode pour récupérer la liste des utilisateurs
     *
     * @param bool $isOrderByName Retourne les utilisateurs triés par noms
     *
     * @return array Tableau contenant la liste des utilisateurs
     */
    public function getAllUsers(bool $isOrderByName = true): array
    {
        $query = "SELECT * FROM users" . ($isOrderByName ? " ORDER BY name" : "");
        try {
            $stmt = $this->db->prepare($query);
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
    public function insert(string $name): bool
    {
        $currentDate = date('Y-m-d');
        $query = "
            INSERT INTO users (name, creation_date, modification_date)
            VALUES (:name, :creation_date, :modification_date)
        ";
        try {
            $stmt = $this->db->prepare($query);
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
     * @param integer $id Id de l'utilisateur
     * @param string $name Nouveau nom de l'utilisateur
     *
     * @return boolean True si la modification a réussie
     */
    public function edit(int $id, string $name): bool
    {
        $currentDate = date("Y-m-d");
        $query = "UPDATE users SET name = :name, modification_date = :modification_date WHERE id = :id";
        try {
            // Préparation de la requête
            $stmt = $this->db->prepare($query);

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
    public function delete(int $id): bool
    {
        $query = "DELETE FROM users WHERE id = :id";
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


