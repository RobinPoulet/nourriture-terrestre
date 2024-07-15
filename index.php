<?php
// On récupére le menu via le cache (ou construction du cache si le cache a plus de 48 heures)
require(__DIR__ . "/classes/Autoloader.php");
Autoloader::register();
$postData = DataFetcher::getData();
if (isset($postData["success"])) {
    $menu = $postData["success"]["menu"];
    $dateMenu = $postData["success"]["date"];
    // On check si il y a un seul dessert cette semaine (ça arrive malheuresement)
    $isOnlyOneDessert = !isset($menu["dessert-2"]);
    $imageUrl = urldecode($postData["success"]["imgSrc"]);
    $figcaption = $postData["success"]["figcaption"];
}
// check si on a bien un article publié cette semaine
if (!HelperDate::isNewMenuAvailable($dateMenu)) {
    Header("Location: bad-day.php");
    die;
}

?>
<!DOCTYPE html>
<html lang="fr">
<?php require(__DIR__ . "/head.php"); ?>
<body>
<?php require(__DIR__ . "/navbar.php"); ?>
    <div class="container-fluid">
        <h2 class="h2 text-center mt-4 p-2">MENU DU <?= $dateMenu ?></h2>
        <p class="text-center mt-4">Cette semaine notre foodtruck favori nous propose : </p>
        <div class="row">
            <div class="col-8">
                <?php if ($isOnlyOneDessert) : ?>
                    <div class="alert alert-info w-75 mt-4 p-4" style="margin-left: auto; margin-right: auto;">⚠️ Il n'y a malheuresement qu'un seul dessert cette semaine 😭</div>
                <?php endif; ?>
                <ul class="list-group w-50 mt-4 p-4" style="margin-left: auto; margin-right: auto;">
                    <?php foreach ($menu as $typePlat => $nomPlat) :?>
                        <li class="list-group-item list-group-item-light">
                            <span class="badge bg-secondary rounded-pill mr-2"><?= $typePlat ?></span>
                            <?= $nomPlat ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-4">
                <div class="text-center w-50 mt-4" style="margin-right: auto;">
                    <img src="<?= $imageUrl ?>" alt="photo du menu" class="rounded mt-4 img-fluid" style="width: auto; height: auto">
                    <p class="text-center mt-4"><small><?= $figcaption ?></small></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>