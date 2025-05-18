<?php
/** @var array $dishes */
?>
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
                                    <label for="dish-<?= $dish->id ?>" class="form-label"><?= $dish->name ?></label>
                                </div>
                                <div class="col-2">
                                    <input type="number" id="dish-<?= $dish->id ?>" class="form-control" name="dishes[<?= $dish->id ?>]" value="0" min="0">
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