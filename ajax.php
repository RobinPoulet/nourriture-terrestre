<?php
require(__DIR__ . "/classes/Database.php");

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST["ajax"]) 
    && $_POST["ajax"] === "add"
) {
    $response = [];
    $name = $_POST["name"];
 
    if (
        empty($name)
        || !is_string($name)
    ) {
        $response["errors"][] = [
            "message" => "Il faut un nom pour l'utilisateur",
            "type"    => "addUser"
        ];
    }
    
    $result = Database::insertUser($name);
    if ($result) {
        $response["success"] = "L'utilisateur ".$name." a bien été enregistré";
    } else {
        // La requête a échoué, renvoyer une réponse d'erreur
        $response["error"] = [
            "message" => "Erreur lors de l'ajout de l'utilisateur ".$name,
            "type"    => "request"
        ];
    }
    
    echo json_encode($response);
    die();
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST["ajax"]) 
    && $_POST["ajax"] === "edit"
) {
    $response = [];
    $name = $_POST["name"];
    $id = $_POST["id"];
 
    if (
        empty($name)
        || !is_string($name)
        || is_null($name)
    ) {
        $response["errors"][] = [
            "message" => "Il faut un nom pour l'utilisateur",
            "type"    => "editUser"
        ];
    }
    
    if (
        !is_integer($id)
        || is_null($name)
    ) {
        $response["errors"][] = [
            "message" => "L'ID utilisateur n'est pas valide",
            "type"    => "editUser"
        ];
    }
    
    $result = Database::editUser($id, $name);
    if ($result) {
        $response["success"] = "Le nom de l'utilisateur a bien été modifié";
    } else {
        // La requête a échoué, renvoyer une réponse d'erreur
        $response["error"] = [
            "message" => "Erreur lors de la modification du nom de l'utilisateur ",
            "type"    => "request"
        ];
    }
    
    echo json_encode($response);
    die();
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST["ajax"]) 
    && $_POST["ajax"] === "delete"
) {
    $response = [];
    $userId = $_POST["id"];
    $user = Database::getOneUser($userId);
    
    $result = Database::deleteUser($userId);
    
    if ($result) {
        $response["success"] = "L'utilisateur ".$user["NAME"]." a bien été supprimé";
    } else {
        // La requête a échoué, renvoyer une réponse d'erreur
        $response["error"] = [
            "message" => "Erreur lors de la suppression de ".$user["NAME"],
            "type"    => "request"
        ];
    }
    
    echo json_encode($response);
    die();
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST["ajax"]) 
    && $_POST["ajax"] === "getAllUsers"
) {
    $response = [];
    
    $users = Database::getAllUsers();
    
    if (!isset($users["error"])) {
        $response["success"] = $users;
    } else {
        // La requête a échoué, renvoyer une réponse d'erreur
        $response = $users;
    }
    
    echo json_encode($response);
    die();
}

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
    
    $result = Database::insertOrder($userId, $order, $perso);
    
    if ($result) {
        $user = Database::getOneUser($userId);
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