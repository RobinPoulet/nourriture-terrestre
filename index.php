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
    header("Location: bad-day-error-page.php");
    die();
}

?>
<!DOCTYPE html>
<html>
<?php require(__DIR__ . "/head.html"); ?>
<body>
</div>
    <div class="container py-5 w">
        <div class="row">
            <h1 class="title text-left" style="margin-top: 30px; margin-bottom: 30px;">🌭 Nourriture Terrestre 🍔 </h1>
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
                        <h5 class="card-header text-center">Fais ta commande : </h5>
                        <form class="" id="order-form" method="POST">
                            <div class="m-3 text-center">
                                <input class="form-input" type="text" name="user" placeholder="Nom ...">
                            </div>
                            <div class="card-body">
                                <div class="m-3">
                                    <div class="row">
                                        <div class="col-4"></div>
                                        <div class="col-4">
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
                                        <div class="col-4"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="m-3 d-flex">
                                        <?php
                                            foreach ([$menu["plat 1"], $menu["plat 2"]] as $index => $plat) {
                                                echo "<div class=\"col-6\">
                                                        <div class=\"form-check form-check-inline\">
                                                            <input 
                                                                class=\"form-check-input\" 
                                                                type=\"checkbox\" 
                                                                id=\"inlineCheckboxPlat".$index."
                                                                name=\"plat-".$index."\">
                                                            <label class=\"form-check-label\" for=\"inlineCheckboxPlat".$index."\">
                                                                ".$plat."
                                                            </label>
                                                        </div>
                                                    </div>";
                                            }
                                        ?>
                                    </div>
                                    <div class="m-3 d-flex">
                                        <?php
                                            foreach ([$menu["dessert 1"], $menu["dessert 2"]] as $index => $plat) {
                                                echo "<div class=\"col-6\">
                                                        <div class=\"form-check form-check-inline\">
                                                            <input 
                                                                class=\"form-check-input\" 
                                                                type=\"checkbox\" 
                                                                id=\"inlineCheckboxPlat".$index."
                                                                name=\"plat-".$index."\">
                                                            <label class=\"form-check-label\" for=\"inlineCheckboxPlat".$index."\">
                                                                ".$plat."
                                                            </label>
                                                        </div>
                                                    </div>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="m-3 text-center">
                                <input hidden name="ajax" value="order">
                                <input 
                                    value="Valider ma commande" 
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
<script>
    $(document).ready(function () {
        $("#order-validate").click(function () {
            // Récupérer les données du formulaire
            const formData = $("#order-form").serialize();
            $.ajax({
                type: "POST",
                url: "order.php",
                data: formData,
                success: function(response) {
                    const data = JSON.parse(response);
                    console.log(data, JSON.parse(data[1]))
                    createDivAlert(data)
                    document.getElementById('form-card').style.display = 'none';
                }
            });
        });
    });
    // Fonction pour créer et afficher un toast
function createDivAlert(data) {
    [name, order] = data
    const root = document.getElementById('div-alert')
    const alerte = document.createElement('div');
    alerte.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
  </symbol>
  </svg>
    <div class="alert alert-success d-flex align-items-center" role="alert">
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
  <div>
  Bonjour ${name}, ta comande est bien enregirstrée
  </div>
</div>
    `;

    // Ajouter le toast au container
    root.appendChild(alerte);
}
function deleteOrder(orderId) {
        $.ajax({
                type: "POST",
                url: "display-orders.php",
                data: {
                    ajax: "deleteOrder",
                    deleteId: orderId
                },
                success: function (response) {
                    const data = JSON.parse(response)
                    console.log(data);
                    const id = "trid" + data.deleted;
                    const tr = document.getElementById(id);
                    tr.remove();
                }
            });
     }

</script>
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