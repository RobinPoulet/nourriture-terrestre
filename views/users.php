<?php
/** @var \App\Model\User[] $users Utilisateurs */
/** @var string $createUserUrl Url pour créer un utilisateur */
/** @var string $editUserUrl Url pour éditer un utilisateur */
/** @var string $deleteUserUrl Url pour supprimer un utilisateur */
?>
<div class="container mt-4 w-50">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Liste des utilisateurs</h2>
        <!-- Utilisation du bouton pour ouvrir la modale d'ajout -->
        <button
                class="btn btn-outline-primary"
                data-bs-toggle="modal"
                data-bs-target="#addUserModal"
                data-url="<?= $createUserUrl ?>"
        >Ajouter un utilisateur</button>
    </div>

    <table class="table table-striped mt-3 mb-3">
        <thead>
            <tr>
                <th>Nom</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="tbodyUsers">
            <?php foreach ($users as $user) :?>
                <tr>
                    <td><?= htmlspecialchars($user->name ?? '') ?></td>
                    <td class="text-end">
                        <button
                            class="btn btn-outline-warning btn-sm btn-edit "
                            data-bs-toggle="modal"
                            data-bs-target="#addUserModal"
                            data-user-name="<?= htmlspecialchars($user->name ?? '') ?>"
                            data-user-id="<?= $user->id ?? 0 ?>"
                        ><i class="bi bi-pencil-square"></i></button>
                        <button
                            class="btn btn-outline-danger btn-sm btn-edit"
                            onclick="confirmDelete(<?= $user->id ?? 0 ?>)"
                        ><i class="bi bi-trash3"></i></button>
                        <input type="hidden" id="complete-url" value="<?= COMPLETE_URL ?>"
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include_once __DIR__ . '/partials/user_edit_modal.php';