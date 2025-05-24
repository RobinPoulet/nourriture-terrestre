<?php
/** @var \App\Model\Dish[] $dishes */
/** @var \App\Model\Order[] $orders */
/** @var string $dateMenu */
/** @var array $tabTotalQuantity */
/** @var ?int $selectedUserId */
?>
<div class="container mt-5">
    <?php if (!empty($dishes) && isset($dateMenu)) : ?>
        <?php if (!empty($orders)) : ?>
            <!-- Résumé rapide -->
            <div class="row text-center mb-4">
                <div class="col-md-4">
                    <div class="summary-card">
                        <h5 class="text-muted mb-1">👥 Commandes totales</h5>
                        <h3 class="fw-bold"><?= count($orders) ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-card">
                        <h5 class="text-muted mb-1">🍽️ Plats servis</h5>
                        <h3 class="fw-bold"><?= array_sum($tabTotalQuantity ?? []) ?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="summary-card">
                        <h5 class="text-muted mb-1">📅 Date</h5>
                        <h3 class="fw-bold"><?= date("Y-m-d") ?></h3>
                    </div>
                </div>
            </div>

            <!-- Tableau commandes -->
            <div class="table-responsive shadow rounded fade-in">
                <table class="table table-custom table-bordered align-middle text-center sticky-header">
                    <thead>
                    <tr>
                        <th scope="col">👤 Nom</th>
                        <?php foreach ($dishes as $dish) : ?>
                            <th scope="col"><?= htmlspecialchars(explode(" ", $dish->name)[0]) ?></th>
                        <?php endforeach; ?>
                        <th scope="col">📝 Perso</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order) :
                        $user = $order->user();
                        $dishesJson = json_encode($order->dishes(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS);
                        ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($user->name) ?></td>
                            <?php foreach ($order->dishes() as $dish) : ?>
                                <td><?= $dish->pivot->quantity ?></td>
                            <?php endforeach; ?>
                            <td><?= nl2br(htmlspecialchars($order->perso ?? "")) ?></td>
                            <td>
                                <?php if ($selectedUserId === $user->id) : ?>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button
                                                class="btn btn-sm btn-outline-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editOrderModal"
                                                data-perso="<?= htmlspecialchars($order->perso ?? '') ?>"
                                                data-username="<?= htmlspecialchars($user->name) ?>"
                                                data-order-id="<?= $order->id ?>"
                                                data-order-dishes="<?= htmlspecialchars($dishesJson) ?>"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button
                                                class="btn btn-sm btn-outline-danger"
                                                id="delete-order-button"
                                                data-order-id="<?= $order->id ?>"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                        <input type="hidden" id="complete-url" value="<?= COMPLETE_URL ?>">
                                    </div>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <tr class="fw-bold table-secondary">
                        <td>Total</td>
                        <?php foreach ($dishes as $dish) : ?>
                            <td><?= ($tabTotalQuantity[$dish->id] ?? 0) ?></td>
                        <?php endforeach; ?>
                        <td colspan="2"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php elseif (isset($orders['error'])) : ?>
            <div class="alert alert-danger text-center mt-4"><?= $orders['error'] ?></div>
        <?php else : ?>
            <div class="alert alert-info text-center mt-4">Pas de commande aujourd'hui</div>
        <?php endif; ?>
    <?php else : ?>
        <div class="alert alert-info text-center mt-4">Erreur lors de la récupération des informations du menu</div>
    <?php endif; ?>

    <?php include_once __DIR__ . '/partials/order_edit_modal.php'; ?>
</div>
<style>
    :root {
        --primary: #6c63ff;
        --secondary: #ffd166;
        --accent: #06d6a0;
        --light: #f8f9fa;
        --text-dark: #343a40;
    }

    .summary-card {
        background: var(--light);
        border-left: 6px solid var(--primary);
        border-radius: 0.5rem;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .fade-in {
        animation: fadeInTable 0.7s ease-in-out;
    }

    @keyframes fadeInTable {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .sticky-header thead th {
        position: sticky;
        top: 0;
        background-color: var(--primary);
        color: white;
        z-index: 1;
    }

    .table-custom tbody tr:hover {
        background-color: #f2f4ff;
    }
</style>