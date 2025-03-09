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

$dateMenu = "";
$dishes = [];
$imgSrc = "";
if (isset($postData["success"])) {
    $dishes = $postData["success"]["dishes"];
    $dateMenu = $postData["success"]["menu"]["CREATION_DATE"];
    $imgSrc = $postData["success"]["menu"]["IMG_SRC"];
    $figcaption = $postData["success"]["menu"]["IMG_FIGCAPTION"];
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
    <div class="w-50" style="margin-left: auto; margin-right: auto;">
        <ul class="list-group mt-4 p-4">
            <?php foreach ($dishes as $dish) : ?>
                <li class="list-group-item list-group-item-light">
                    <?= $dish["NAME"] ?>
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
