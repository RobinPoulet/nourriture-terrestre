<?php
// On récupére le menu via le cache (ou construction du cache si le cache a plus de 48 heures)
require(__DIR__ . "/classes/Autoloader.php");
Autoloader::register();
$postData = DataFetcher::getData();
if (isset($postData["success"])) {
    $menu = $postData["success"]["menu"];
    $dateMenu = $postData["success"]["date"];
    $canDisplayForm = HelperDate::canDisplayOrderForm($dateMenu);
    $canDisplayForm = true;
}
$users = Database::getAllUsers();
?>
<!DOCTYPE html>
<html lang="fr">
<?php require(__DIR__ . "/head.php"); ?>
<body>
<?php require(__DIR__ . "/navbar.php"); ?>
    <div class="container-fluid">
    <?php if (isset($menu) && isset($dateMenu)) : ?>
        <?php if ($canDisplayForm) : ?>
            <div id="div-alert"></div>
            <div id="div-alert-request"></div>
            <div 
                id="form-card" 
                class="card" 
                style="width: 34rem; margin-left: auto; margin-right: auto; margin-top: 20px; padding: 4px;"
            >
                <h5 class="card-header text-center">Fais ta commande : </h5>
                <form id="order-form" method="POST">
                    <div class="card-body">
                        <div id="div-alert-user"></div>
                        <div class="form-group m-3">
                            <select class="form-select w-100" id="user-select" data-placeholder="Sélectionner un nom" name="user">
                                <option value="" disabled selected hidden>Sélectionner un nom</option>
                                <?php foreach ($users as $user) : ?>
                                    <option value="<?= $user["ID"] ?? 0 ?>"><?= $user["NAME"] ?? ""?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group m-3 list-group">
                            <div id="div-alert-order"></div>
                            <?php foreach ($menu as $titrePlat => $nomPlat) :?>
                                <label class="list-group-item">
                                    <input class="form-check-input me-1" type="checkbox" name="<?= $titrePlat ?>">
                                    <?= $nomPlat ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <div class="form-group m-3">
                            <span class="input-group-text">Personnalisation</span>
                            <textarea class="form-control" aria-label="With textarea" name="perso"></textarea>
                        </div>
                        <div class="m-3 text-center">
                            <input type="hidden" value="order" name="ajax">
                            <input value="Commander" class="btn btn-dark" onclick="orderValidate()">
                        </div>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-info m-4 p-4" style="margin-left: auto; margin-right: auto;">
              La prise de commande n'est possible que le lundi matin AVANT 11h45 🕛  
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info m-4 p-4" style="margin-left: auto; margin-right: auto;">
            Erreur lors de la récupération des informations du menu
        </div>
    <?php endif; ?>
    </div>
<!-- Toast notification -->
<div class="position-fixed top-50 start-50 p-3" style="z-index: 11">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <strong class="me-auto">Nourriture Terrestre</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="liveToastContent">
        </div>
    </div>
</div>
</body>
</html>