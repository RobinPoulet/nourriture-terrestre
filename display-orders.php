<?php
// On récupére le menu via le cache (ou construction du cache si le cache a plus de 48 heures)
require(__DIR__ . "/classes/Autoloader.php");
Autoloader::register();
try {
    $postData = DataFetcher::getData();
    $menu = $postData["menu"];
    $dateMenu = $postData["date"];
} catch (\Exception $e) {
    // Rediriger vers la page d'erreur avec le message d'erreur encodé dans l'URL
    $errorMessage = rawurlencode($e->getMessage());
    header("Location: error.php?message=$errorMessage");
    exit;
}
?>
<!DOCTYPE html>
<html>

<?php require_once(__DIR__ . "/head.html"); ?>

<body>
<?php 
require(__DIR__ . "/navbar.php");

$menu = $postData["menu"];
$currentDate = date("Y-m-d");
$resultsOrder = Database::getOrdersByCreationDate($currentDate);
?>
<?php if (count($resultsOrder) > 0) : ?>
    <h2 class=\"h3 text-center p-2\">Récap des commandes ".$currentDate."</h2>
    <div class=\"container\">
        <table class=\"table table-striped table\">
            <thead>
                <tr>
                    <th scope=\"col\">Nom</th>
                    <?php
                    foreach ($menu as $dish) {
                        $shortDish = explode(" ", $dish)[0];
                        echo "
                        <th scope=\"col\">".$shortDish."</th>
                        ";
                    }
                    echo "
                        <th scope=\"col\">Perso</th>
                    </tr>
                    </thead>
                    <tbody>
                    ";
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
                    ";
                    foreach ($totalOrders as $totalOrder) {
                        echo "
                            <td class=\"col\"><bold>".$totalOrder."</bold></td>
                        ";
                    }
                    echo "
                            <td class=\"col\"></td>
                        </tr>
                    </tbody>
                    </table>
                    </div>
                    ";
                    ?>
<?php else: ?>
    <div class="alert alert-info m-4 p-4" style="margin-left: auto; margin-right: auto;">
        Il n'y a pas encore eu de commande aujourd'hui
    </div>
<?php endif; ?>
</body>
</html>