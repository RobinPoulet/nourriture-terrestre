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
    
    $result = Database::insertOrder($user, $order, $perso);
    var_dump($result);
    
    if ($result) {
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
} 