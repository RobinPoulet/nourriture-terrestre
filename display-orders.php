<?php
require(__DIR__ . "/classes/Autoloader.php");
Autoloader::register();
// On récupére le menu via le cache (ou construction du cache si le cache a plus de 48 heures)
$postData = DataFetcher::getData();
$menu = [];
if (isset($postData["success"])) {
    $menu = $postData["success"]["menu"];
    $dateMenu = $postData["success"]["date"];
    $resultsOrder = Database::getTodayOrders();
    $users = Database::getAllUsers();
}
?>
<!DOCTYPE html>
<html>

<?php require_once(__DIR__ . "/head.php"); ?>

<body>
<?php 
require(__DIR__ . "/navbar.php");
?>
<?php if (isset($menu) && isset($dateMenu)) : ?>
    <?php if (!isset($resultsOrder["error"]) && count($resultsOrder) > 0) : ?>
        <h2 class="h3 text-center p-2">Récap des commandes du <?= date("Y-m-d") ?></h2>
        <div class="container">
            <table class="table table-striped table">
                <thead>
                    <tr>
                        <th scope="col">Nom</th>
                        <?php foreach ($menu as $dish) :?>
                            <th scope="col"><?= explode(" ", $dish)[0] ?></th>
                        <?php endforeach; ?>
                        <th scope="col">Perso</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $totalOrders = [];
                    foreach ($menu as $type => $name) {
                        $totalOrders[$type] = 0;
                    }
                ?>
                <?php foreach ($resultsOrder as $result) :?>
                <?php
                    $decodedJson = json_decode($result["CONTENT"]);
                    $orders = [];
                    $perso = $result["PERSO"] ?? "";
                    foreach ($decodedJson as $key => $value) {
                        $orders[] = $key;
                        $totalOrders[$key]++;
                    }
                    $user = Database::getOneUser($result["USER_ID"]);
                ?>
                    <tr>
                        <td class="col"><?= $user["NAME"] ?></td>
                        <?php foreach ($totalOrders as $dish => $order) :?>
                            <td class="col"><?= (in_array($dish, $orders, true) ? "X" : "") ?></td>
                        <?php endforeach; ?>
                        <td class="col"><?= $perso ?></td>
                        <td class="col">
                            <button
                                    class="btn btn-warning btn-sm btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editOrderModal"
                                    data-order='<?= json_encode($resultsOrder) ?>'
                                    data-username="<?= $user["NAME"] ?>"
                            >Modifier
                            </button>
                            <button
                                    class="btn btn-danger btn-sm btn-edit"
                                    onclick="confirmOrderDelete(<?= $result["ID"] ?>)"
                            >Supprimer
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                    <tr>
                        <td class="col">TOTAL</td>
                        <?php foreach ($totalOrders as $totalOrder) :?>
                            <td class="col"><bold><?= $totalOrder ?></bold></td>
                        <?php endforeach; ?>
                        <td class="col"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php elseif (isset($resultsOrder["error"])) : ?>
        <div class="alert alert-danger m-4 p-4" style="margin-left: auto; margin-right: auto;">
            <?= $resultsOrder["error"] ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info m-4 p-4" style="margin-left: auto; margin-right: auto;">
            Pas de commande aujourd'hui
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="alert alert-info m-4 p-4" style="margin-left: auto; margin-right: auto;">
        Erreur lors de la récupération des informations du menu
    </div>
<?php endif; ?>
<!-- Modale pour Editer une commande -->
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <form id="edit-order-form" method="POST">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOrderModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group m-3 list-group">
                        <div id="div-alert-order"></div>
                        <?php foreach ($menu as $titrePlat => $nomPlat) : ?>
                            <label class="list-group-item">
                                <input class="form-check-input me-1" type="checkbox" name="<?= $titrePlat ?>"
                                       id="<?= $titrePlat ?>">
                                <?= $nomPlat ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-group m-3">
                        <span class="input-group-text">Personnalisation</span>
                        <textarea class="form-control" aria-label="With textarea" name="perso" id="perso"></textarea>
                    </div>
                </div>
                <input type="hidden" value="edit-order" name="ajax">
                <input type="hidden" id="order-id" name="order-id">
                <div class="modal-footer">
                    <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                    >Annuler
                    </button>
                    <button
                            type="button"
                            class="btn btn-primary"
                            id="order-edit-validate"
                    >Modifier
                    </button>
                </div>
            </div>
        </div>
    </form>
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