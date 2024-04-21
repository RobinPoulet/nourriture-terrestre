<?php
// Afficher toutes les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
// On récupére le menu via le cache (ou construction du cache si le cache a plus de 48 heures)
require(__DIR__ . "/classes/Autoloader.php");
Autoloader::register();
$postData = DataFetcher::getData();
var_dump($postData);
if (isset($postData["success"])) {
    $menu = $postData["success"]["menu"];
    $dateMenu = $postData["success"]["date"];
} elseif (isset($postData["error"])) {
    echo $postData["error"];
}
?>
<!DOCTYPE html>
<html>
<?php require(__DIR__ . "/head.html"); ?>
<body>
<?php require(__DIR__ . "/navbar.php"); ?>
    <div class="container-fluid">
        <h2 class="h3 text-center mt-4 p-2">Menu du <?= $dateMenu ?></h2>
        <ul class="list-group w-50 mt-4 p-4" style="margin-left: auto; margin-right: auto;">
            <?php
            foreach ($menu as $typePlat => $nomPlat) {
                echo "
                    <li class=\"list-group-item list-group-item-light\">
                        <span class=\"badge bg-primary rounded-pill mr-2\">".$typePlat."</span>
                         " . $nomPlat . "
                    </li>
                ";
            }
            ?>
        </ul>
    </div>
</body>

</html>