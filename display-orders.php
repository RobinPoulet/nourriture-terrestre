<?php
require(__DIR__ . "/classes/Autoloader.php");
Autoloader::register();
// On récupére le menu via le cache (ou construction du cache si le cache a plus de 48 heures)
$postData = DataFetcher::getData();
if (isset($postData["success"])) {
    $menu = $postData["success"]["menu"];
    $dateMenu = $postData["success"]["date"];
    $resultsOrder = Database::getTodayOrders();
    $users = Database::getAllUsers();
}
?>
<!DOCTYPE html>
<html>

<?php require_once(__DIR__ . "/head.php"); ?>

<body>
<?php 
require(__DIR__ . "/navbar.php");
?>
<?php if (isset($menu) && isset($dateMenu)) : ?>
    <?php if (!isset($resultsOrder["error"]) && count($resultsOrder) > 0) : ?>
        <h2 class="h3 text-center p-2">Récap des commandes du <?= date("Y-m-d") ?></h2>
        <div class="container">
            <table class="table table-striped table">
                <thead>
                    <tr>
                        <th scope="col">Nom</th>
                        <?php foreach ($menu as $dish) :?>
                            <th scope="col"><?= explode(" ", $dish)[0] ?></th>
                        <?php endforeach; ?>
                        <th scope="col">Perso</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $totalOrders = [];
                    foreach ($menu as $type => $name) {
                        $totalOrders[$type] = 0;
                    }
                ?>
                <?php foreach ($resultsOrder as $result) :?>
                <?php
                    $decodedJson = json_decode($result["CONTENT"]);
                    $orders = [];
                    $perso = $result["PERSO"] ?? "";
                    foreach ($decodedJson as $key => $value) {
                        $orders[] = $key;
                        $totalOrders[$key]++;
                    }
                    $user = Database::getOneUser($result["USER_ID"]);
                ?>
                    <tr id="tr<?= $user["NAME"] ?>">
                        <td class="col"><?= $user["NAME"] ?></td>
                        <?php foreach ($totalOrders as $dish => $order) :?>
                            <td class="col"><?= (in_array($dish, $orders, true) ? "X" : "") ?></td>
                        <?php endforeach; ?>
                        <td class="col"><?= $perso ?></td>
                    </tr>
                <?php endforeach; ?>
                    <tr>
                        <td class="col">TOTAL</td>
                        <?php foreach ($totalOrders as $totalOrder) :?>
                            <td class="col"><bold><?= $totalOrder ?></bold></td>
                        <?php endforeach; ?>
                        <td class="col"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php elseif (isset($resultsOrder["error"])) : ?>
        <div class="alert alert-danger m-4 p-4" style="margin-left: auto; margin-right: auto;">
            <?= $resultsOrder["error"] ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info m-4 p-4" style="margin-left: auto; margin-right: auto;">
            Pas de commande aujourd'hui
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="alert alert-info m-4 p-4" style="margin-left: auto; margin-right: auto;">
        Erreur lors de la récupération des informations du menu
    </div>
<?php endif; ?>
</body>
</html>