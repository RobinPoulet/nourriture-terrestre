<?php
/** @var \App\Model\User[] $users */
/** @var \App\Model\Dish[] $dishes */
/** @var string $dateMenu */
/** @var string $createOrderUrl */
/** @var bool $canDisplayForm */
/** @var int $selectedUserId */
?>
<div class="container mt-4">
    <?php if (!empty($dishes) && isset($dateMenu)) : ?>
    <?php if ($canDisplayForm) : ?>
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
                                <option value="<?= $user->id ?>" <?= ($user->id === $selectedUserId ? "selected" : "") ?>>
                                   <?= htmlspecialchars($user->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Liste des plats -->
                    <div class="form-group m-3">
                        <?php foreach ($dishes as $index => $dish) : ?>
                            <div class="row mb-3">
                                <div class="col-10">
                                    <label for="dish-<?= $index ?>" class="form-label"><?= $dish->name ?></label>
                                </div>
                                <div class="col-2">
                                    <input type="number" id="dish-<?= $index ?>" class="form-control" name="dishes[<?= $dish->id ?>]" value="0" min="0">
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