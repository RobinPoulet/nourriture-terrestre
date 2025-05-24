<?php
/** @var string $createUserUrl */
?>
<!-- Modale pour Ajouter un utilisateur -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Ajouter un utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= $createUserUrl ?>" method="post" id="form-user">
                <div class="modal-body">
                    <div class="mb-3">
                        <div id="alert-user-modal"></div>
                    </div>
                    <div class="mb-3">
                        <label for="userName" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="userName" name="name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >Annuler</button>
                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="user-validate"
                    >Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>
