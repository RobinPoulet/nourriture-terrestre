<?php

class Menus
{
    private PDO $db;

    public function __construct()
    {
        // Initialisation de l'instance de la base de données
        $this->db = Database::getInstance();
    }

    public function insert(string $imgSrc, string $imgFigcaption, string $creationDate): array
    {
        $returnValue = [];

        $currentDate = date("Y-m-d");
        $query = "INSERT INTO menus (
            img_src,
            img_figcaption,
            creation_date,
            modification_date
        ) VALUES (
            :img_src,
            :img_figcaption,
            :creation_date,
            :modification_date
        )";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':img_src', $imgSrc);
            $stmt->bindParam(':img_figcaption', $imgFigcaption);
            $stmt->bindParam(':creation_date', $creationDate);
            $stmt->bindParam(':modification_date', $currentDate);

            if ($stmt->execute()) {
                $returnValue = [
                    "statut" => "success",
                    "id"     => (int)$this->db->lastInsertId()
                ];
            }

        } catch (PDOException $e) {
            $returnValue = [
                "statut"  => "error",
                "message" => "Erreur lors de l'insertion du menu : " . $e->getMessage()
            ];
        }

        return $returnValue;
    }

    public function findByDate(string $dateCreation): ?array
    {
        $returnValue = null;

        $query = "SELECT * FROM menus WHERE creation_date= :creation_date";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':creation_date', $dateCreation);
            $stmt->execute();

            $menu = $stmt->fetch(PDO::FETCH_ASSOC);

            $returnValue = ($menu !== false ? $menu : null);
        } catch (PDOException $e) {
            $returnValue = [
                "error" => "Erreur lors de la récupération du menu : " . $e->getMessage()
            ];
        }

        return $returnValue;
    }

    // Récupérer le dernier menu de la base de donnée
    public function getLastMenu(): ?array
    {
        $returnValue = null;

        $query = "SELECT * FROM menus ORDER BY creation_date DESC LIMIT 1";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $menu = $stmt->fetch(PDO::FETCH_ASSOC);

            $returnValue = ($menu !== false ? $menu : null);
        } catch (PDOException $e) {
            $returnValue = [
                "error" => "Erreur lors de la récupération du menu : " . $e->getMessage()
            ];
        }

        return $returnValue;
    }
}