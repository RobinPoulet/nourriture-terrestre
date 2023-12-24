<?php
require(__DIR__ . "/checkDates.php");
// On récupére le menu via le cache (ou construction du cache si le cache a plus de 48 heures)
require(__DIR__ . "/get-menu.php");

$commandDate = new DateTime();

// Formatage date
$format = "Y-m-d\TH:i:s";
$dateMenu = $postData["date"];
$menu = $postData["menu"];
$dateFormatee = $commandDate->format($format);

$checkDate = passerCommande($dateMenu, $dateFormatee);
if ($checkDate !== "Commande passée avec succès.") {
    header("Location: error.php");
    die();
}

?>
<!DOCTYPE html>
<html>
<?php require(__DIR__ . "/head.html"); ?>
<body>
</div>
    <div class="container p-4">
        <div class="row">
            <h1 class="title text-left" style="margin-top: 30px; margin-bottom: 30px;">&#x1F963; Nourriture Terrestre 🍔</h1>
            <div class="col-6">
                <div class="list">
                    <h2 class="list-title" style="margin-left: 35px;">Le Menu</h2>
                    <ul>
                        <?php
                        foreach ($menu as $value) {
                            echo "<li class=\"item\"><span class=\"name\">" . $value . "</span</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
            </div>
            <div class="row mb-4">
                <div class="col-6 ml-2">
                    <div id="div-alert"></div>
                    <div 
                        id="form-card" 
                        class="card" 
                        style="width: 34rem; margin_left: auto; margin-right: auto; margin-top: 20px; padding: 4px;"
                    >
                        <h5 class="card-header ml-3">Fais ta commande : </h5>
                        <form id="order-form" method="POST">
                            <div class="card-body">
                                <div class="form-group m-3">
                                    <label for="your-name" class="form-label">Your Name</label>
                                    <input type="text" class="form-control" id="your-name" name="user" required>
                                </div>
                                <div class="form-group m-3">
                                    <div class="form-check">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            id="flexCheckEntree" 
                                            name="entree"
                                        >
                                        <label class="form-check-label" for="flexCheckEntree">
                                            <?= $menu["entree"] ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="m-3 d-flex">
                                    <div class="col-6">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckboxPlat1"
                                                name="plat-1">
                                            <label class="form-check-label" for="inlineCheckboxPlat1">
                                                <?= $menu["plat 1"] ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckboxPlat2"
                                                name="plat-2">
                                            <label class="form-check-label" for="inlineCheckboxPlat2">
                                                <?= $menu["plat 2"] ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="m-3 d-flex">
                                    <div class="col-6">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckboxDessert1"
                                                name="dessert-1">
                                            <label class="form-check-label" for="inlineCheckboxDessert1">
                                                <?= $menu["dessert 1"] ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckboxDessert2"
                                                name="dessert-2">
                                            <label class="form-check-label" for="inlineCheckboxDessert2">
                                                <?= $menu["dessert 2"] ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="m-3 text-center">
                                <input hidden name="ajax" value="order">
                                <input 
                                    value="Commander" 
                                    class="btn btn-primary"
                                    id="order-validate"
                                >
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<style type="text/css" media="screen">
    body {
        background: linear-gradient(to right,
                rgba(255, 0, 0, 0) 50%,
                rgba(85, 230, 193, 1) 100%);
    }

    .item {
        border-bottom: 1px dotted #c5c5c5;
        break-inside: avoid;
        display: flex;
        justify-content: space-between;
    }

    span {
        font-family: "Teko", sans-serif;
        margin-bottom: -4px;
        background: #fff;
        padding: 4px;
        color: #555;
    }

    .list-title {
        font-family: "Teko", sans-serif;
        font-size: 30px;
        color: #e67437;
        margin-bottom: 32px;
    }

    .list {
        margin-bottom: 32px
    }
</style>