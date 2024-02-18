<?php
require(__DIR__ . "/db-connexion.php");
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST["ajax"]) 
    && $_POST["ajax"] === "order"
) {
    $response = [];
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
    if (empty(json_decode($order))) {
        $response["errors"][] = [
            "message" => "Il faut commander au moins un article",
            "type" => "order"
        ];
    }
    $user = $_POST["user"];
    if (empty($user)) {
        $response["errors"][] = [
            "message" => "Le nom est obligatoire",
            "type" => "user"
        ];
    }
    if (!empty($response["errors"])) {
        echo json_encode($response);
        die;
    }
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
            $response["success"] = "Ta commande a bien été enregistrée " . $user;
            echo json_encode($response);
            die();
        } else {
            // La requête a échoué, renvoyer une réponse d'erreur
            $response["error"] = [
                "message" => "Erreur lors de l'enregistrement de la commande.",
                "type" => "request"
            ];
            echo json_encode($response);
            die();
        }
    } catch (PDOException $e) {
        $response["error"] = [
            "message" => "Erreur : " . $e->getMessage(),
            "type" => "request"
        ];
        echo json_encode($errors);
        die();
    }
} 