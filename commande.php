<?php
if (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) || $_SERVER['SERVER_NAME'] === 'localhost') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
// On récupére le menu via le cache (ou construction du cache si le cache a plus de 48 heures)
require(__DIR__ . "/classes/Autoloader.php");
Autoloader::register();
$objUser = new Users();
$postData = DataFetcher::getData();
$canDisplayForm = false;
if (isset($postData["success"])) {
    $menu = $postData["success"]["menu"];
    $dateMenu = $postData["success"]["date"];
    $canDisplayForm = HelperDate::canDisplayOrderForm($dateMenu);
}
$users = $objUser->getAllUsers();
//$canDisplayForm = true;
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
                            <label for="user-select"></label>
                            <select class="form-select w-100" id="user-select" data-placeholder="Sélectionner un nom" name="user">
                                <option value="" disabled selected hidden>Sélectionner un nom</option>
                                <?php foreach ($users as $user) : ?>
                                    <option value="<?= $user["ID"] ?>"><?= $user["NAME"]?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group m-3 list-group">
                            <div id="div-alert-order"></div>

                            <?php foreach ($menu as $titrePlat => $nomPlat) : ?>
                                <form class="mx-auto" style="max-width: 300px;">
                                    <label for="quantity-input" class="form-label">Choose quantity:</label>
                                    <div class="input-group">
                                        <!-- Bouton de décrémentation -->
                                        <button
                                                type="button"
                                                id="decrement-button"
                                                data-input-counter-decrement="quantity-input"
                                                class="btn btn-outline-secondary"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                                                <path d="M3.5 8a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 0 1h-8a.5.5 0 0 1-.5-.5z"/>
                                            </svg>
                                        </button>

                                        <!-- Champ de saisie -->
                                        <input
                                                type="text"
                                                id="quantity-input"
                                                class="form-control text-center"
                                                placeholder="999"
                                                required
                                                aria-describedby="helper-text-explanation"
                                                style="max-width: 80px;"
                                        />

                                        <!-- Bouton d'incrémentation -->
                                        <button
                                                type="button"
                                                id="increment-button"
                                                data-input-counter-increment="quantity-input"
                                                class="btn btn-outline-secondary"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div id="helper-text-explanation" class="form-text">
                                        Please select a number from 0 to 999.
                                    </div>
                                </form>

                                <label class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?= htmlspecialchars($nomPlat, ENT_QUOTES, 'UTF-8') ?></span>
                                    <input
                                            class="form-control w-25 text-end ms-3"
                                            type="number"
                                            min="0"
                                            value="0"
                                            name="<?= htmlspecialchars($titrePlat, ENT_QUOTES, 'UTF-8') ?>"
                                    >
                                </label>
                            <?php endforeach; ?>


                        </div>
                        <div class="form-group m-3">
                            <span class="input-group-text">Personnalisation</span>
                            <textarea class="form-control" aria-label="With textarea" name="perso"></textarea>
                        </div>
                        <div class="m-3 text-center">
                            <input type="hidden" value="order" name="ajax">
                            <label>
                                <input value="Commander" class="btn btn-dark" onclick="orderValidate()">
                            </label>
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