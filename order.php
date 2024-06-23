<?php
require(__DIR__ . "/classes/Database.php");
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
    $userId = (int) $_POST["user"];
    if (is_null($userId)) {
        $response["errors"][] = [
            "message" => "L'utilisateur choisi n'existe pas en base",
            "type" => "user"
        ];
    }
    if (!empty($response["errors"])) {
        echo json_encode($response);
        die;
    }
    $perso = $_POST["perso"];
    $currentDate = date('Y-m-d');
    
    $result = Database::insertOrder($userId, $order, $perso);
    
    if ($result) {
        $user = Database::getOneUser($userId);
        $response["success"] = "Ta commande a bien été enregistrée " . $user["NAME"];
    } else {
        // La requête a échoué, renvoyer une réponse d'erreur
        $response["error"] = [
            "message" => "Erreur lors de l'enregistrement de la commande.",
            "type" => "request"
        ];
    }
    echo json_encode($response);
    die();
} 