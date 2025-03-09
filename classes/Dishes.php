<?php

class Dishes
{
    private PDO $db;

    public function __construct()
    {
        // Initialisation de l'instance de la base de données
        $this->db = Database::getInstance();
    }

    /**
     * Retrouvé un plat avec son nom
     *
     * @param string $name Nom du plat
     *
     * @return ?array Le plat si il est trouvé
     */
    public function findOneByName(string $name): ?array
    {
        $query = "SELECT * FROM dishes WHERE name= :name";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->execute();

            $dish = $stmt->fetch(PDO::FETCH_ASSOC);

            return ($dish !== false ? $dish : null);
        } catch (PDOException $e) {
            return [
                "error" => "Erreur lors de la récupération du plat : " . $e->getMessage()
            ];
        }
    }

    /**
     * Retrouvé un plat avec son Id
     *
     * @param integer $id Id du p
     *
     * @return ?array L'utilisateur si il est trouvé
     */
    public function findOneById(int $id): ?array
    {
        $query = "SELECT * FROM dishes WHERE id= :id";
        try {
            // Préparation de la requête
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute(); // Exécution de la requête

            // Récupération du résultat
            $dish = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourner l'utilisateur ou null s'il n'est pas trouvé
            return ($dish !== false ? $dish : null);
        } catch (PDOException $e) {
            return [
                "error" => "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage()
            ];
        }
    }

    /**
     * Retrouvé les plats d'un menu
     *
     * @param integer $menuId Id du menu
     *
     * @return ?array Les plats du menu en id
     */
    public function findByMenuId(int $menuId): ?array
    {
        $query = "SELECT * FROM dishes WHERE menu_id= :menu_id";
        try {
            // Préparation de la requête
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':menu_id', $menuId);
            $stmt->execute(); // Exécution de la requête

            // Récupération du résultat
            $dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Retourner les plats ou null s'il y en a aucun
            return ($dishes !== false ? $dishes : null);
        } catch (PDOException $e) {
            return [
                "error" => "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage()
            ];
        }
    }



    /**
     * Méthode pour récupérer la liste des plats
     *
     * @param bool $isOrderByName Retourne les plats triés par noms
     *
     * @return array
     */
    public function findAll(bool $isOrderByName = true): array
    {
        $query = "SELECT * FROM dishes" . ($isOrderByName ? " ORDER BY name" : "");
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
     * Ajouter un nouveu plat
     *
     * @param string $name Nom du plat
     *
     * @return boolean True si l'insertion a réussi
     */
    public function insert(string $name, int $total, int $menuId): bool
    {
        $currentDate = date('Y-m-d');
        $query = "INSERT INTO dishes (
            name,
            total,
            menu_id,
            creation_date,
            modification_date
        ) VALUES (
            :name,
            :total,
            :menu_id,
            :creation_date,
            :modification_date
        )";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':menu_id', $menuId);
            $stmt->bindParam(':creation_date', $currentDate);
            $stmt->bindParam(':modification_date', $currentDate);
            // Exécuter la requête et vérifier le succès
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Modifier la date et le total d'un plat
     *
     * @param integer $id     Id de l'utilisateur
     * @param int     $total  Nouveau total du nombre de fois ou le plat est apparu
     * @param int     $menuId Id du menu
     *
     * @return boolean True si la modification a réussie
     */
    public function update(int $id, int $total, int $menuId): bool
    {
        $currentDate = date("Y-m-d");
        $query = "UPDATE dishes SET total = :total, menu_id = :menu_id, modification_date = :modification_date WHERE id = :id";
        try {
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':menu_id', $menuId);
            $stmt->bindParam(':modification_date', $currentDate);
            $stmt->bindParam(':id', $id);

            $returnValue = $stmt->execute();
        } catch (PDOException $e) {
            $returnValue = false;
        }

        return $returnValue;
    }

    public function updateExistingDishes(array $dishes): bool
    {
        foreach ($dishes as $dish) {

        }
    }

    /**
     * Supprimer un utilisateur
     *
     * @param integer $id Id de l'utilisateur
     *
     * @return boolean True si la suppression à réussie
     */
    public function deleteUser(int $id): bool
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