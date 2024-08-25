<?php
if (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) || $_SERVER['SERVER_NAME'] === 'localhost') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
// On récupére le menu via le cache (ou construction du cache si le cache a plus de 48 heures)
require(__DIR__ . "/classes/Autoloader.php");
Autoloader::register();
$postData = DataFetcher::getData();
$isOnlyOneDessert = false;
$dateMenu = "";
$menu = [];
$imgSrc = "";
if (isset($postData["success"])) {
    $menu = $postData["success"]["menu"];
    $dateMenu = $postData["success"]["date"];
    $imgSrc = $postData["success"]["imgSrc"];
    $figcaption = $postData["success"]["figcaption"];
    // On check si il y a un seul dessert cette semaine (ça arrive malheuresement)
    $isOnlyOneDessert = !isset($menu["dessert-2"]);
    // check si on a bien un article publié cette semaine
    if (!HelperDate::isNewMenuAvailable($dateMenu)) {
        Header("Location: bad-day.php");
        die;
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<?php require(__DIR__ . "/head.php"); ?>
<body>
<?php require(__DIR__ . "/navbar.php"); ?>
<?php if (isset($postData["success"])) : ?>
<div class="container-fluid">
    <h2 class="h2 text-center mt-4 p-2">MENU DU <?= $dateMenu ?></h2>
    <?php if ($isOnlyOneDessert) : ?>
        <div class="alert alert-info w-75 mt-4 p-4" style="margin-left: auto; margin-right: auto;">⚠️ Il n'y a
            malheuresement qu'un seul dessert cette semaine 😭
        </div>
    <?php endif; ?>
    <div class="w-50" style="margin-left: auto; margin-right: auto;">
        <ul class="list-group mt-4 p-4">
            <?php foreach ($menu as $typePlat => $nomPlat) : ?>
                <li class="list-group-item list-group-item-light">
                    <span class="badge bg-secondary rounded-pill mr-2"><?= $typePlat ?></span>
                    <?= $nomPlat ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="text-center mt-4 p-4">
            <img src="<?= $imgSrc ?>" alt="photo du menu" class="rounded mt-4 img-fluid"
                 style="width: auto; max-height: 300px">
            <?php if (!empty($figcaption)) : ?>
                <p class="text-center mt-4"><small><?= $figcaption ?></small></p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
<?php else: ?>
    <div class="alert-danger">Erreur lors de la récupération des données</div>
<?php endif; ?>
</html>
