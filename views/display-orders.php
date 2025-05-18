<?php
/** @var \App\Model\Dish[] $dishes */
/** @var \App\Model\Order[] $orders */
/** @var string $dateMenu */
/** @var array $tabTotalQuantity */
/** @var ?int $selectedUserId */
?>
<div class="container mt-4">
<?php if (!empty($dishes) && isset($dateMenu)) : ?>
    <?php if (!empty($orders)) : ?>
        <h2 class="h3 text-center p-2">Récap des commandes du <?= date("Y-m-d") ?></h2>

            <table class="table table-striped table table-responsive">
                <thead>
                    <tr>
                        <th scope="col">Nom</th>
                        <?php foreach ($dishes as $dish) :?>
                            <th scope="col"><?= explode(" ", $dish->name)[0] ?></th>
                        <?php endforeach; ?>
                        <th scope="col">Perso</th>
                        <th scope="col" colspan="100%"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order) :?>
                <?php
                    $user = $order->user();
                    $dishesJson = json_encode($order->dishes(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS);
                    ?>
                    <tr>
                        <td class="col"><?= $user->name ?></td>
                        <?php foreach ($order->dishes() as $dish) :?>
                            <td class="col"><?= $dish->pivot->quantity ?></td>
                        <?php endforeach; ?>
                        <td class="col"><?= ($order->perso ?? "") ?></td>
                        <?php if ($selectedUserId === $user->id) : ?>
                            <td class="col">
                                <button
                                        class="btn btn-outline-warning btn-sm btn-edit "
                                        data-bs-toggle="modal"
                                        data-bs-target="#editOrderModal"
                                        data-perso="<?= ($order->perso ?? "") ?>"
                                        data-username="<?= $user->name ?>"
                                        data-order-id="<?= $order->id ?>"
                                        data-order-dishes="<?= htmlspecialchars($dishesJson) ?>"
                                ><i class="bi bi-pencil-square"></i>
                                </button>
                                <button
                                        class="btn btn-outline-danger btn-sm btn-edit"
                                        id="delete-order-button"
                                        data-order-id="<?= $order->id ?>"
                                ><i class="bi bi-trash3"></i>
                                </button>
                                <input type="hidden" id="complete-url" value="<?= COMPLETE_URL ?>"
                            </td>
                        <?php else :?>
                            <td class="col">
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                    <tr>
                        <td class="col">TOTAL</td>
                        <?php foreach ($dishes as $dish) :?>
                            <td class="col" style="font-weight: bold"><?= ($tabTotalQuantity[$dish->id] ?? 0) ?></td>
                        <?php endforeach; ?>
                        <td class="col" colspan="100%"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php elseif (isset($orders["error"])) : ?>
        <div class="alert alert-danger m-4 p-4" style="margin-left: auto; margin-right: auto;">
            <?= $orders["error"] ?>
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
<?php include_once __DIR__ . '/partials/order_edit_modal.php';