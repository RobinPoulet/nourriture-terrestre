<?php
require(__DIR__ . "/db-connexion.php");
// On récupére le menu via le cache (ou construction du cache si le cache a plus de 48 heures)
require(__DIR__ . "/get-menu.php");
$dateMenu = $postData["date"];
$menu = $postData["menu"];
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
<?php require(__DIR__ . "/navbar.html"); ?>
<h2 class="h3 text-center p-2">Récap des commandes du <?= $currentDate ?></h2>
<div class="container">
    <table class="table table-striped table">
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <?php
                foreach ($menu as $dish) {
                    $shortDish = explode(" ", $dish)[0];
                    echo "
                        <th scope=\"col\">".$shortDish."</th>
                    ";
                }
                ?>
                <th scope="col">Perso</th>
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
            $perso = $result["PERSO"] ?? "";
            foreach ($decodedJson as $key => $value) {
                $orders[] = $key;
                $totalOrders[$key]++;
            }
            echo "
                <tr id=\"tr".$result["ID"]."\">
                    <td class=\"col\">".$result["NAME"]."</td>
                    <td class=\"col\">".(in_array("entree", $orders) ? "X" : "")."</td>
                    <td class=\"col\">".(in_array("plat-1", $orders) ? "X" : "")."</td>
                    <td class=\"col\">".(in_array("plat-2", $orders) ? "X" : "")."</td>
                    <td class=\"col\">".(in_array("dessert-1", $orders) ? "X" : "")."</td>
                    <td class=\"col\">".(in_array("dessert-2", $orders) ? "X" : "")."</td>
                    <td class=\"col\">".$perso."</td>
                </tr>
            ";
        }
        echo "
            <tr>
                <td class=\"col\">TOTAL</td>
                <td class=\"col\"><bold>".$totalOrders["entree"]."</bold></td>
                <td class=\"col\"><bold>".$totalOrders["plat-1"]."</bold></td>
                <td class=\"col\"><bold>".$totalOrders["plat-2"]."</bold></td>
                <td class=\"col\"><bold>".$totalOrders["dessert-1"]."</bold></td>
                <td class=\"col\"><bold>".$totalOrders["dessert-2"]."</bold></td>
                <td class=\"col\"></td>
            </tr>
        "
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
