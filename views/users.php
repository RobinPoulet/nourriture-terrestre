<?php
/** @var array $users Utilisateurs */

session_start();
$tabFlashMessage = $_SESSION['tab_flash_message'] ?? null;
unset($_SESSION['tab_flash_message']); // Supprime après affichage
?>
<!DOCTYPE html>
<html lang="fr">
<?php require(__DIR__ . "/../head.php"); ?>
<body>
<?php require(__DIR__ . "/../navbar.php"); ?>
<div id="div-alert-request" class="mt-3"></div>
<div class="container mt-5 w-50">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Liste des Utilisateurs</h2>
        <!-- Utilisation du bouton pour ouvrir la modale d'ajout -->
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Ajouter un utilisateur</button>
    </div>

    <?php if (!empty($tabFlashMessage['errors'])): ?>
        <?php foreach ($tabFlashMessage['errors'] as $error): ?>
            <div class="alert alert-danger ?> mt-3"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($tabFlashMessage['success'])): ?>
        <div class="alert alert-success ?> mt-3"><?= htmlspecialchars($tabFlashMessage['success']) ?></div>
    <?php endif; ?>

    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Nom</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="tbodyUsers">
            <?php foreach ($users as $user) :?>
                <tr>
                    <td><?= htmlspecialchars($user["NAME"] ?? "") ?></td>
                    <td class="text-end">
                        <button
                            class="btn btn-outline-warning btn-sm btn-edit "
                            data-bs-toggle="modal"
                            data-bs-target="#addUserModal"
                            data-user-name="<?= htmlspecialchars($user["NAME"] ?? "") ?>"
                            data-user-id="<?= $user["ID"] ?? 0 ?>"
                        ><i class="bi bi-pencil-square"></i></button>
                        <button
                            class="btn btn-outline-danger btn-sm btn-edit"
                            onclick="confirmDelete(<?= $user["ID"] ?? 0 ?>)"
                        ><i class="bi bi-trash3"></i></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modale pour Ajouter un utilisateur -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Ajouter un Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="http://localhost/nourriture-terrestre/create-user" method="post" id="form-user">
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