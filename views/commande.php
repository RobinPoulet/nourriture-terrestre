<?php
/** @var array $users */
/** @var array $dishes */
/** @var string $dateMenu */
/** @var string $createOrderUrl */
/** @var bool $canDisplayForm */
/** @var int $selectedUserId */
?>
<!DOCTYPE html>
<html lang="fr">
<?php require(__DIR__ . "/../head.php"); ?>
<body>
<?php require(__DIR__ . "/../navbar.php"); ?>
<div class="container-fluid">
    <?php if (!empty($dishes) && isset($dateMenu)) : ?>
    <?php if ($canDisplayForm) : ?>
        <div id="div-alert"></div>
        <div id="div-alert-request"></div>
        <div
                id="form-card"
                class="card"
                style="width: 34rem; margin-left: auto; margin-right: auto; margin-top: 20px; padding: 4px;"
        >
            <h5 class="card-header text-center bg-dark text-white">Fais ta commande :</h5>

            <form action="<?= $createOrderUrl ?>" id="order-form" method="POST">
                <div class="card-body">
                    <div class="form-group m-3">
                        <label for="user-select" class="form-label">Sélectionner un utilisateur</label>
                        <select class="form-select" id="user-select" data-placeholder="Sélectionner un nom" name="user">
                            <option value="" disabled <?= ($selectedUserId === null ? "selected" : "") ?> hidden>Sélectionner un nom</option>
                            <?php foreach ($users as $user) : ?>
                                <option value="<?= $user["ID"] ?>" <?= ($user["ID"] === $selectedUserId ? "selected" : "") ?>>
                                   <?= htmlspecialchars($user["NAME"]) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Liste des plats -->
                    <div class="form-group m-3">
                        <?php foreach ($dishes as $index => $dish) : ?>
                            <div class="row mb-3">
                                <div class="col-10">
                                    <label for="dish-<?= $index ?>" class="form-label"><?= $dish["NAME"] ?></label>
                                </div>
                                <div class="col-2">
                                    <input type="number" id="dish-<?= $index ?>" class="form-control" name="dishes[<?= $dish["ID"] ?>]" value="0" min="0">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Section de personnalisation -->
                    <div class="form-group m-3">
                        <label for="perso" class="form-label">Personnalisation</label>
                        <textarea class="form-control" aria-label="With textarea" name="perso" id="perso"></textarea>
                    </div>

                    <!-- Bouton de validation de commande -->
                    <div class="m-3 text-center">
                        <button type="submit" class="btn btn-dark">Commander</button>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
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