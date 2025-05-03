<?php
/** @var array $dishes */
/** @var string $dateMenu */
/** @var array $displayResults */
/** @var array $tabTotalQuantity */
/** @var array $users */
/** @var ?int $selectedUserId */

session_start();
$tabFlashMessage = ($_SESSION["tab_flash_message"] ?? null);
unset($_SESSION["tab_flash_message"]);
?>
<!DOCTYPE html>
<html lang="fr">
<?php require(__DIR__ . "/../head.php"); ?>
<body>
<?php require(__DIR__ . "/../navbar.php"); ?>
<?php if (!empty($dishes) && isset($dateMenu)) : ?>
    <?php if (!isset($resultsOrder["error"]) && !empty($displayResults)) : ?>
        <h2 class="h3 text-center p-2">Récap des commandes du <?= date("Y-m-d") ?></h2>
        <div class="container">

            <?php if (!empty($tabFlashMessage["errors"])): ?>
                <?php foreach ($tabFlashMessage["errors"] as $error): ?>
                    <div class="alert alert-danger ?> mt-3"><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (isset($tabFlashMessage["success"])): ?>
                <div class="alert alert-success ?> mt-3"><?= htmlspecialchars($tabFlashMessage["success"]) ?></div>
            <?php endif; ?>

            <table class="table table-striped table table-responsive">
                <thead>
                    <tr>
                        <th scope="col">Nom</th>
                        <?php foreach ($dishes as $dish) :?>
                            <th scope="col"><?= explode(" ", $dish["NAME"])[0] ?></th>
                        <?php endforeach; ?>
                        <th scope="col">Perso</th>
                        <th scope="col" colspan="100%"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($displayResults as $orderId => $result) :?>
                <?php
                    $user = $users[$result["user_id"]];
                    $json = json_encode($result, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS);
                    ?>
                    <tr>
                        <td class="col"><?= $user ?></td>
                        <?php foreach ($dishes as $dish) :?>
                            <td class="col"><?= ($result["dishes"][$dish["ID"]] ?? "") ?></td>
                        <?php endforeach; ?>
                        <td class="col"><?= ($result["perso"] ?? "") ?></td>
                        <?php if ($selectedUserId === $result["user_id"]) :?>
                            <td class="col">
                            <button
                                    class="btn btn-outline-warning btn-sm btn-edit "
                                    data-bs-toggle="modal"
                                    data-bs-target="#editOrderModal"
                                    data-order="<?= htmlspecialchars($json) ?>"
                                    data-username="<?= $user ?>"
                                    data-order-id="<?= $orderId ?>"
                            ><i class="bi bi-pencil-square"></i>
                            </button>
                            <button
                                    class="btn btn-outline-danger btn-sm btn-edit"
                                    id="delete-order-button"
                                    data-order-id="<?= $orderId ?>"
                            ><i class="bi bi-trash3"></i>
                            </button>
                        </td>
                        <?php else :?>
                            <td class="col">
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                    <tr>
                        <td class="col">TOTAL</td>
                        <?php foreach ($dishes as $dish) :?>
                            <td class="col" style="font-weight: bold"><?= ($tabTotalQuantity[$dish["ID"]] ?? 0) ?></td>
                        <?php endforeach; ?>
                        <td class="col" colspan="100%"></td>
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
                        <?php foreach ($dishes as $index => $dish) : ?>
                            <div class="row mb-3">
                                <div class="col-10">
                                    <label for="dish-<?= $dish["ID"] ?>" class="form-label"><?= $dish["NAME"] ?></label>
                                </div>
                                <div class="col-2">
                                    <input type="number" id="dish-<?= $dish["ID"] ?>" class="form-control" name="dishes[<?= $dish["ID"] ?>]" value="0" min="0">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-group m-3">
                        <span class="input-group-text">Personnalisation</span>
                        <textarea class="form-control" aria-label="With textarea" name="perso" id="perso"></textarea>
                    </div>
                </div>
                <input type="hidden" id="input-user-name" name="user-name">
                <div class="modal-footer">
                    <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                    >Annuler
                    </button>
                    <button
                            type="submit"
                            class="btn btn-primary"
                            id="order-edit-validate"
                    >Modifier
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
</body>
</html>