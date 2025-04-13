<?php

namespace App\Entity;
use App\Database\Database;
use PDO;
use PDOException;

class Menus
{
    private PDO $db;

    public function __construct()
    {
        // Initialisation de l'instance de la base de données
        $this->db = Database::getInstance();
    }

    public function insert(string $imgSrc, string $imgFigcaption, bool $isOpen, string $creationDate): ?int
    {
        $returnValue = null;

        $currentDate = date('Y-m-d H:i:s');
        $query = "INSERT INTO menus (
            img_src,
            img_figcaption,
            is_open,
            creation_date,
            modification_date
        ) VALUES (
            :img_src,
            :img_figcaption,
            :is_open,
            :creation_date,
            :modification_date
        )";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':img_src', $imgSrc);
            $stmt->bindParam(':img_figcaption', $imgFigcaption);
            $stmt->bindParam(':is_open', $isOpen);
            $stmt->bindParam(':creation_date', $creationDate);
            $stmt->bindParam(':modification_date', $currentDate);

            if ($stmt->execute()) {
                $returnValue = (int)$this->db->lastInsertId();
            }

        } catch (PDOException $e) {
            $returnValue = null;
        }

        return $returnValue;
    }

    public function findOneByDate(string $dateCreation): ?array
    {
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

    public function findOneById(int $id): ?array
    {
        $query = "SELECT * FROM menus WHERE id= :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
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