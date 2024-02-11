<?php
require(__DIR__ . "/db-connexion.php");
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST["ajax"]) 
    && $_POST["ajax"] === "order"
) {
    // récupérer unniquement les différentes composantes de la commande
    $order = json_encode(
        // On filtre sur les clés de $_POST pour ne garder que ce qui concerne la commande
        array_filter($_POST, function ($key) {
            return (
                $key !== "user" 
                && $key !== "ajax" 
                && $key !== "perso"
            );
        }, ARRAY_FILTER_USE_KEY)
    );
    $user = $_POST["user"];
    $perso = $_POST["perso"];
    $currentDate = date('Y-m-d');
    $query = "INSERT INTO orders (name, content, perso, creation_date) VALUES (:name, :order, :perso, :creation_date)";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $user);
        $stmt->bindParam(':order', $order);
        $stmt->bindParam(':perso', $perso);
        $stmt->bindPAram(':creation_date', $currentDate);
         // Exécuter la requête et vérifier le succès
        $success = $stmt->execute();
    
        if ($success) {
            echo json_encode([$user,$order]);
            die();
        } else {
            // La requête a échoué, renvoyer une réponse d'erreur
            echo json_encode("Erreur lors de l'enregistrement de la commande.");
            die();
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        die();
    }
} 