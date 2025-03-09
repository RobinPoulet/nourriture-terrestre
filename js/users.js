$(document).ready(function () {
    const pathname = window.location.pathname;
    const pageName = pathname.split('/').pop();
    if (pageName === 'users.php') {
        // Sélectionner les éléments nécessaires
        const addUserModal = document.getElementById('addUserModal');
        const userNameInput = document.getElementById('userName');
        const userValidateButton = document.getElementById('user-validate');


        /**
         * Fonction pour réinitialiser la modale au mode ajout
         */
        function resetModalToAddMode() {
            document.getElementById('addUserModalLabel').textContent = 'Ajouter un Utilisateur';
            userNameInput.value = '';
            userValidateButton.textContent = 'Ajouter';
            userValidateButton.onclick = function () {
                addUser();
            };
        }

        // Gestion de l'événement de l'ouverture de la modale
        addUserModal.addEventListener('show.bs.modal', function (event) {
            // Bouton qui a déclenché la modale
            const button = event.relatedTarget;

            // Récupérer les données du bouton
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');

            if (
                userId
                && userName
            ) {
                // Si l'édition est en cours
                document.getElementById('addUserModalLabel').textContent = 'Éditer un Utilisateur';
                userNameInput.value = userName;
                userValidateButton.textContent = 'Modifier';
                userValidateButton.onclick = function () {
                    confirmEdit(userId);
                }
            } else {
                // Sinon, réinitialiser au mode ajout
                resetModalToAddMode();
            }
        });

        // Réinitialiser la modale lorsqu'elle est fermée
        addUserModal.addEventListener('hidden.bs.modal', function () {
            resetModalToAddMode();
        });
    }
});

/**
 * AJAX pour l'ajout d'utilisateur
 */
function addUser() {
    const data = {
        name : document.getElementById('userName').value,
        ajax: 'add'
    };
    $.ajax({
        type: 'POST',
        url: 'ajax-users.php',
        data: data,
        success: function (response) {
            ajaxSuccess(response, 'users.php');
        },
        error: function(xhr, status, error) {
            ajaxError(error);
        }
    });
}

/**
 * Confirmer l'édition d'un utilisateur
 *
 * @param {int} userId Id de l'utilisateur
 */
function confirmEdit(userId) {
    if (confirm('Êtes-vous sûr de vouloir modifier cet utilisateur ?')) {
        editUser(userId);
    }
}

/**
 * AJAX pour modifier un utilisateur 
 *
 * @param {int} userId Id de l'utilisateur
 */
function editUser(userId) {
    const data = {
        name : document.getElementById('userName').value,
        id: userId,
        ajax: 'edit'
    };
    $.ajax({
        type: 'POST',
        url: 'ajax-users.php',
        data: data,
        success: function (response) {
            ajaxSuccess(response, 'users.php');
        },
        error: function(xhr, status, error) {
            ajaxError(error);
        }
    });
}

/**
 * Confirmer la supression d'un utilisateur 
 *
 * @param {int} userId Id de l'utilisateur
 */
function confirmDelete(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        deleteUser(userId);
    }
}

/**
 * AJAX pour supprimer un utilisateur
 *
 * @param {int} userId Id de l'utilisateur
 */
function deleteUser(userId) {
    const data = {
        ajax : 'delete',
        id : userId
    }
    $.ajax({
        type: 'POST',
        url: 'ajax-users.php',
        data: data,
        success: function (response) {
            ajaxSuccess(response, 'users.php');
        },
        error: function(xhr, status, error) {
            ajaxError(error);
        }
    });
}