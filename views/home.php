<?php
/** @var string $dateMenu */
/** @var string $imgSrc */
?>
<!DOCTYPE html>
<html lang="fr">
<?php require(__DIR__ . "/../head.php"); ?>
<body>
<?php require(__DIR__ . "/../navbar.php"); ?>
<?php if (!empty($dishes)) : ?>
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
