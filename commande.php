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
?>
<!DOCTYPE html>
<html>
<?php require(__DIR__ . "/head.html"); ?>
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
                            <input type="text" class="form-control" name="user" placeholder="Nom" required>
                        </div>
                        <div class="form-group m-3 list-group">
                            <div id="div-alert-order"></div>
                            <?php
                            foreach ($menu as $titrePlat => $nomPlat) {
                                echo "
                                    <label class=\"list-group-item\">
                                    <input class=\"form-check-input me-1\" type=\"checkbox\" name=".$titrePlat.">
                                    ".$nomPlat."
                                    </label>
                                ";
                            }
                            ?>
                        </div>
                        <div class="form-group m-3">
                            <span class="input-group-text">Personnalisation</span>
                            <textarea class="form-control" aria-label="With textarea" name="perso"></textarea>
                        </div>
                        <div class="m-3 text-center">
                            <input hidden name="ajax" value="order">
                            <input 
                                value="Commander" 
                                class="btn btn-dark"
                                id="order-validate"
                            >
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
</body>
</html>