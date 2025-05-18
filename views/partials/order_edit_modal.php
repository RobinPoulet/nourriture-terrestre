<?php
/** @var array $dishes */
?>
<!-- Modale pour Editer une commande -->
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <form id="edit-order-form" method="POST">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg rounded-4 border-0 animate__animated animate__fadeInDown">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-bold" id="editOrderModalLabel">Modifier la commande</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body bg-light-subtle">
                    <div id="div-alert-order" class="mb-3"></div>

                    <div class="row g-3">
                        <?php foreach ($dishes as $index => $dish) : ?>
                            <div class="col-12 col-md-6 d-flex align-items-center bg-white border rounded-3 p-2 shadow-sm">
                                <i class="bi bi-egg-fried me-2 fs-4 text-primary"></i>
                                <label for="dish-<?= $dish->id ?>" class="form-label mb-0 flex-grow-1"><?= $dish->name ?></label>
                                <input
                                        type="number"
                                        id="dish-<?= $dish->id ?>"
                                        class="form-control form-control-sm text-center w-25"
                                        name="dishes[<?= $dish->id ?>]"
                                        value="0"
                                        min="0"
                                >
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-group mt-4">
                        <label for="perso" class="form-label fw-semibold">Personnalisation</label>
                        <textarea
                                class="form-control"
                                name="perso"
                                id="perso"
                                rows="3"
                                placeholder="Ex : Sans sauce, bien cuit, etc..."
                        ></textarea>
                    </div>
                </div>

                <input type="hidden" id="input-user-name" name="user-name">

                <div class="modal-footer bg-white rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        ✅ Enregistrer les modifications
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<style>
    #editOrderModal .modal-content {
        animation: fadeInModal 0.3s ease-out;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
    }

    @keyframes fadeInModal {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    #editOrderModal label {
        font-weight: 500;
        color: var(--text-dark);
    }

    #editOrderModal .form-control {
        border-radius: 0.5rem;
    }

    #editOrderModal textarea {
        min-height: 80px;
    }

    #editOrderModal .sms-btn {
        background-color: var(--accent);
        color: white;
        border: none;
    }

    #editOrderModal .sms-btn:hover {
        background-color: #04c18c;
    }
</style>