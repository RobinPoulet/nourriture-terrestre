<?php
// On récupére le menu via le cache (ou construction du cache si le cache a plus de 48 heures)
require(__DIR__ . "/get-menu.php");
$menu = $postData["menu"];
?>
<!DOCTYPE html>
<html>
<?php require(__DIR__ . "/head.html"); ?>
<body>
<?php require(__DIR__ . "/navbar.html"); ?>
    <div class="container-fluid">
        <div id="div-alert"></div>
        <div 
            id="form-card" 
            class="card" 
            style="width: 34rem; margin-left: auto; margin-right: auto; margin-top: 20px; padding: 4px;"
        >
            <h5 class="card-header text-center">Fais ta commande : </h5>
            <form id="order-form" method="POST">
                <div class="card-body">
                    <div class="form-group m-3">
                        <input type="text" class="form-control" name="user" placeholder="Nom" required>
                    </div>
                    <div class="form-group m-3 list-group">
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
    </div>
</body>
</html>

