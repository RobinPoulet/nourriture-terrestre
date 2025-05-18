<?php
/** @var \App\Model\User[] $users */
/** @var \App\Model\Dish[] $dishes */
/** @var string $dateMenu */
/** @var string $createOrderUrl */
/** @var bool $canDisplayForm */
/** @var int $selectedUserId */
?><div class="container py-5">
    <?php if (!empty($dishes) && isset($dateMenu)) : ?>
        <?php if ($canDisplayForm) : ?>
            <div id="form-card" class="card shadow-lg rounded-4 mx-auto" style="max-width: 600px;">
                <div class="card-header bg-dark text-white text-center rounded-top-4">
                    <h5 class="mb-0">🍽️ Passe ta commande</h5>
                </div>

                <form action="<?= $createOrderUrl ?>" id="order-form" method="POST">
                    <div class="card-body p-4">

                        <!-- Sélection utilisateur -->
                        <div class="mb-4">
                            <label for="user-select" class="form-label fw-semibold">👤 Choisis ton nom</label>
                            <select class="form-select" id="user-select" name="user" required>
                                <option value="" disabled <?= ($selectedUserId === null ? "selected" : "") ?> hidden>Sélectionner un nom</option>
                                <?php foreach ($users as $user) : ?>
                                    <option value="<?= $user->id ?>" <?= ($user->id === $selectedUserId ? "selected" : "") ?>>
                                        <?= htmlspecialchars($user->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Liste des plats -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">🍛 Choisis tes plats</h6>
                            <?php foreach ($dishes as $index => $dish) : ?>
                                <div class="row align-items-center mb-2">
                                    <div class="col-8">
                                        <label for="dish-<?= $index ?>" class="form-label"><?= htmlspecialchars($dish->name) ?></label>
                                    </div>
                                    <div class="col-4">
                                        <input type="number" id="dish-<?= $index ?>" name="dishes[<?= $dish->id ?>]"
                                               class="form-control text-center" value="0" min="0">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Personnalisation -->
                        <div class="mb-4">
                            <label for="perso" class="form-label fw-semibold">📝 Commentaires ou préférences</label>
                            <textarea class="form-control" name="perso" id="perso" rows="3" placeholder="Ex : sans sauce, bien cuit..."></textarea>
                        </div>

                        <!-- Bouton de commande -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-dark px-4 py-2 rounded-pill">
                                <i class="bi bi-send-fill me-2"></i>Valider la commande
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<style>
    .card {
        border: none;
        background: #fff;
    }

    .form-label {
        font-size: 0.95rem;
    }

    textarea.form-control {
        resize: vertical;
    }

    input[type="number"]::-webkit-inner-spin-button {
        opacity: 1;
    }
</style>
