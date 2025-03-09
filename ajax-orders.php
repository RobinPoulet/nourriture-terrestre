<?php
require(__DIR__ . "/classes/Autoloader.php");
Autoloader::register();
$objOrders = new Orders();
$objUsers = new Users();

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
    $userId = null;
    if (empty($_POST["user"])) {
        $response["errors"][] = [
            "message" => "Merci de sélectionner un nom",
            "type" => "user"
        ];
    } else {
        $userId = (int) $_POST["user"];
    }
    if (!empty($response["errors"])) {
        echo json_encode($response);
        die;
    }
    $perso = $_POST["perso"];
    $currentDate = date('Y-m-d');

    $result = $objOrders->insertOrder($userId, $order, $perso);
    
    if ($result) {
        $user = $objUsers->getOneUser($userId);
        $response["success"] = "Ta commande a bien été enregistrée";
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

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST["ajax"])
    && $_POST["ajax"] === "edit-order"
) {
    $response = [];
    $orderId = $_POST["order-id"];
    // récupérer unniquement les différentes composantes de la commande
    $order = json_encode(
    // On filtre sur les clés de $_POST pour ne garder que ce qui concerne la commande
        array_filter($_POST, function ($key) {
            return (
                $key !== "perso"
                && $key !== "ajax"
                && $key !== "order-id"
            );
        }, ARRAY_FILTER_USE_KEY)
    );
    if (empty(json_decode($order))) {
        $response["errors"][] = [
            "message" => "Il faut commander au moins un article",
            "type" => "order"
        ];
    }
    if (!empty($response["errors"])) {
        echo json_encode($response);
        die;
    }
    $perso = $_POST["perso"] ?? "";
    $currentDate = date('Y-m-d');

    $result = $objOrders->editOrder($orderId, $order, $perso);

    if ($result) {
        $orderData = $objOrders->getOneOrder($orderId);
        $response["success"] = "Ta commande a bien été modifiée";
    } else {
        // La requête a échoué, renvoyer une réponse d'erreur
        $response["error"] = "Erreur lors de la modification de la commande";
    }
    echo json_encode($response);
    die();
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST["ajax"])
    && $_POST["ajax"] === "delete-order"
) {
    $response = [];
    $orderId = $_POST["id"];
    $result = $objOrders->deleteOrder($orderId);

    if ($result) {
        $response["success"] = "La commande a bien été supprimé";
    } else {
        // La requête a échoué, renvoyer une réponse d'erreur
        $response["error"] = "Erreur lors de la suppression de la commande";
    }

    echo json_encode($response);
    die();
}
