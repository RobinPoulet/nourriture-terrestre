<?php

if (isset($_SESSION['user_id']) && isset($_SESSION['order'])) {
    $order = json_decode($_SESSION['order']['content']);
    var_dump($order);

    // Requête SQL pour récupérer l'utilisateur
    // Récupérez l'ID de la commande à mettre à jour
    $orderID = $_SESSION['order']['id'];
    // Requête SQL de mise à jour pour ajouter la date actuelle en tant que "modification_date"
    $query = "UPDATE orders SET order_content = :order, modification_date = :modification_date WHERE id = :order_id";
    $date = date("Y-m-d H:i:s");
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':order', $order);
        $stmt->bindParam(':modification_date', $date); // Date actuelle
        $stmt->bindParam(':order_id', $orderID);
        $stmt->execute();
        // Redirerction vers "index.php"
       header("Location: index.php");
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
// Fermer la connexion
$pdo = null;

?>
<div class="card" style="width: 18rem; margin_left: auto; margin-right: auto">
    <h5 class="card-header">Tu peux encore éditer ta commande : </h5>
    <div class="card-body">
        <form method="post">

            <div class="m-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="flexCheckEntree" name="entree" value=<?= $order['entree'] ?? 0 ?>>
                    <label class="form-check-label" for="flexCheckEntree">
                        Entrée
                    </label>
                </div>
            </div>

            <div class="m-3 d-flex">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckboxPlat1" name="plat-1">
                    <label class="form-check-label" for="inlineCheckboxPlat1">Plat 1</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckboxPlat2" name="plat-2">
                    <label class="form-check-label" for="inlineCheckboxPlat2">Plat 2</label>
                </div>
            </div>

            <div class="m-3 d-flex">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckboxDessert1" name="dessert-1">
                    <label class="form-check-label" for="inlineCheckboxDessert1">Dessert 1</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckboxDessert2" name="dessert-2">
                    <label class="form-check-label" for="inlineCheckboxDessert2">Dessert 2</label>
                </div>
            </div>

            <div class="m-3">
                <input type="submit" value="Valider ma commande" class="btn btn-primary">
            </div>

        </form>
    </div>
</div>