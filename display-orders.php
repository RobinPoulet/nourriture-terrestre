<?php
require(__DIR__ . "/db-connexion.php");
// On récupére le menu via le cache (ou construction du cache si le cache a plus de 48 heures)
require(__DIR__ . "/get-menu.php");
require_once(__DIR__ . "/classes/HelperString.php");
$dateMenu = $postData["date"];
$menu = $postData["menu"];
$currentDate = date("Y-m-d");
 $query = "SELECT * FROM orders WHERE creation_date = :creation_date";
 $stmt = $pdo->prepare($query);
 $stmt->bindParam(':creation_date', $currentDate);
 $stmt->execute();
 $resultsOrder = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST["ajax"]) && $_POST["ajax"] === "deleteOrder") {
    $id = intval($_POST["deleteId"]);
    $query = "DELETE FROM orders WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(["deleted" => $id]);
    die();
}

 $currentDate = date("Y-m-d");
 $query = "SELECT * FROM orders WHERE creation_date = :creation_date";
 $stmt = $pdo->prepare($query);
 $stmt->bindParam(':creation_date', $currentDate);
 $stmt->execute();
 $resultsOrder = $stmt->fetchAll(PDO::FETCH_ASSOC);

 ?>
 <!DOCTYPE html>
<html>

<?php require_once(__DIR__ . "/head.html"); ?>

<body>
</div>
    <div class="container p-4">
    <h2 class="h3 text-center p-2">Récap des commandes du <?= $currentDate ?></h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <?php
                foreach ($menu as $dish) {
                    echo "
                        <th scope=\"col\">".HelperString::shortString($dish, 18)."</th>
                    ";
                }
                ?>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $totalOrders = [
            "entree" => 0,
            "plat-1" => 0,
            "plat-2" => 0,
            "dessert-1" => 0,
            "dessert-2" => 0,
        ];
            
        foreach ($resultsOrder as $result) {
            $decodedJson = json_decode($result["CONTENT"]);
            $orders = [];
            foreach ($decodedJson as $key => $value) {
                $orders[] = $key;
                $totalOrders[$key]++;
            }
            echo "
                <tr id=\"tr".$result["ID"]."\">
                    <td>".$result["NAME"]."</td>
                    <td>".(in_array("entree", $orders) ? "X" : "")."</td>
                    <td>".(in_array("plat-1", $orders) ? "X" : "")."</td>
                    <td>".(in_array("plat-2", $orders) ? "X" : "")."</td>
                    <td>".(in_array("dessert-1", $orders) ? "X" : "")."</td>
                    <td>".(in_array("dessert-2", $orders) ? "X" : "")."</td>
                </tr>
            ";
        }
        echo "
            <tr>
                <td>TOTAL</td>
                <td><bold>".$totalOrders["entree"]."</bold></td>
                <td><bold>".$totalOrders["plat-1"]."</bold></td>
                <td><bold>".$totalOrders["plat-2"]."</bold></td>
                <td><bold>".$totalOrders["dessert-1"]."</bold></td>
                <td><bold>".$totalOrders["dessert-2"]."</bold></td>
            </tr>
        "
        ?>
        </tbody>
    </table>
  
    </div>
</body>
</html>
