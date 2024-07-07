<?php
require(__DIR__ . "/classes/Autoloader.php");
Autoloader::register();
?>
<!DOCTYPE html>
<html lang="fr">
<?php require(__DIR__ . "/head.php"); ?>
<body>
<?php require(__DIR__ . "/navbar.php"); ?>
<div id="div-alert-request" class="mt-3"></div>
<div class="container mt-5 w-50">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Liste des Utilisateurs</h2>
        <!-- Utilisation du bouton pour ouvrir la modale d'ajout -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Ajouter un utilisateur</button>
    </div>
    
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success mt-3"><?= htmlspecialchars($_GET['message']) ?></div>
    <?php endif; ?>
    
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Nom</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="tbodyUsers">
            <?php $users = Database::getAllUsers();?>
            <?php foreach ($users as $user) :?>
                <tr>
                    <td><?= htmlspecialchars($user["NAME"] ?? "") ?></td>
                    <td class="text-end">
                        <button 
                            class="btn btn-warning btn-sm btn-edit" 
                            data-bs-toggle="modal" 
                            data-bs-target="#addUserModal" 
                            data-user-name="<?= htmlspecialchars($user["NAME"] ?? "") ?>"
                            data-user-id="<?= $user["ID"] ?? 0 ?>"
                        >Modifier</button>
                        <button 
                            class="btn btn-danger btn-sm" 
                            onclick="confirmDelete(<?= $user["ID"] ?? 0 ?>)"
                        >Supprimer</button>
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
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="userName" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="userName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button 
                        type="button" 
                        class="btn btn-secondary" 
                        data-bs-dismiss="modal"
                    >Annuler</button>
                    <button 
                        type="button"
                        class="btn btn-primary"
                        id="user-validate"
                        onclick="addUser()"
                    >Ajouter</button>
                </div>
        </div>
    </div>
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