$(document).ready(function () {
    const pathname = window.location.pathname;
    const pageName = pathname.split('/').pop();
    if (pageName === 'users.php') {
        // Vérifier s'il y a un message stocké dans le sessionStorage
        const toastMessage = sessionStorage.getItem('toastMessage');

        if (toastMessage) {
            // Afficher le toast avec le message stocké
            displayToast(toastMessage, 'liveToast', 'liveToastContent');

            // Supprimer le message du sessionStorage après affichage
            sessionStorage.removeItem('toastMessage');
        }

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
        url: 'ajax.php',
        data: data,
        success: function (response) {
            ajaxSuccess(response);
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
        url: 'ajax.php',
        data: data,
        success: function (response) {
            ajaxSuccess(response);
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
        url: 'ajax.php',
        data: data,
        success: function (response) {
            ajaxSuccess(response);
        },
        error: function(xhr, status, error) {
            ajaxError(error);
        }
    });
}

function ajaxSuccess(response) {
    // Code pour traiter la réponse AJAX
    const data = JSON.parse(response);
    if (data.success) {
        // Stocker un message de succès dans le sessionStorage
        sessionStorage.setItem('toastMessage', data.success);
        // Déterminer l'URL de redirection en fonction de l'environnement
        let baseUrl = window.location.origin;
        if (baseUrl.includes('localhost')) {
            baseUrl += '/nourriture-terrestre';
        }
        // Rediriger vers l'URL calculée
        window.location.href = baseUrl + '/users.php';
    } else {
        displayToast(data.error.message, 'liveToast', 'liveToastContent');
    }
}

function ajaxError(error) {
    // Afficher un message d'erreur générique en cas d'erreur de requête AJAX
    const toastMessage = "Une erreur s'est produite lors de la requête AJAX : " + error;
    displayToast(toastMessage, 'liveToast', 'liveToastContent');
}