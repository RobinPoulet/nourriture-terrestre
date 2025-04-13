document.addEventListener("DOMContentLoaded", function () {
    const pathname = window.location.pathname;
    const pageName = pathname.split('/').pop();
    if (pageName === 'users') {
        // Sélectionner les éléments nécessaires
        const addUserModal = document.getElementById('addUserModal');
        const userNameInput = document.getElementById('userName');
        const userValidateButton = document.getElementById('user-validate');

        /**
         * Fonction pour réinitialiser la modale au mode ajout
         */
        function resetModalToAddMode() {
            document.getElementById('addUserModalLabel').textContent = 'Ajouter un Utilisateur';
            const formUser = document.getElementById('form-user');
            formUser.action = `http://localhost/nourriture-terrestre/create-user`
            userNameInput.value = '';
            userValidateButton.textContent = 'Ajouter';
        }

        // Gestion de l'événement de l'ouverture de la modale
        addUserModal.addEventListener('show.bs.modal', function (event) {
            const alertUserModal = document.getElementById('alert-user-modal');
            alertUserModal.className = '';
            alertUserModal.textContent = '';
            // Bouton qui a déclenché la modale
            const button = event.relatedTarget;

            // Récupérer les données du bouton
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            console.log(userName, userId);
            if (
                userId
                && userName
            ) {
                // Si l'édition est en cours
                const formUser = document.getElementById('form-user');
                formUser.action = `http://localhost/nourriture-terrestre/edit-user/${userId}`
                document.getElementById('addUserModalLabel').textContent = 'Éditer un Utilisateur';
                userNameInput.value = userName;
                userValidateButton.textContent = 'Modifier';
            } else {
                // Sinon, réinitialiser au mode ajout
                resetModalToAddMode();
            }
        });

        // Réinitialiser la modale lorsqu'elle est fermée
        addUserModal.addEventListener('hidden.bs.modal', function () {
            resetModalToAddMode();
        });

        // Ajout d'événements pour gérer l'action de soumission du formulaire
        document.getElementById('form-user').addEventListener('submit', function(event) {
            const isEdit = document.getElementById('user-validate').textContent === "Modifier";
            handleUserAction(event, isEdit ? 'edit' : 'add');
        });
    }
});

// Fonction pour gérer l'ajout ou la modification
function handleUserAction(event, actionType) {
    const userName = document.getElementById('userName');
    const alertUserModal = document.getElementById('alert-user-modal');

    if (userName.value.trim() === "") {
        event.preventDefault();  // Empêche la soumission si le champ est vide
        alertUserModal.className = 'alert alert-danger';
        alertUserModal.textContent = "Merci de renseigner le champ 'Nom'";
        return;
    }

    if (actionType === 'edit') {
        if (confirm("Êtes-vous sûr de vouloir modifier cet utilisateur ?")) {
            document.getElementById('form-user').submit();
        } else {
            event.preventDefault();  // Annule la soumission si l'utilisateur n'est pas d'accord
        }
    } else {
        document.getElementById('form-user').submit();
    }
}


/**
 * Confirmer la supression d'un utilisateur 
 *
 * @param {int} userId Id de l'utilisateur
 */
function confirmDelete(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        // Redirection vers l'URL de suppression avec le userId
        window.location.href = `http://localhost/nourriture-terrestre/delete-user/${userId}`;
    }
}