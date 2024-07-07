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
        url: 'ajax.php',
        data: data,
        success: async function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                $('#addUserModal').modal('hide');
                displayToast(data.success, 'liveToast', 'liveToastContent');
                const users = await getAllUsers();
                displayUsersTable(users);
            } else {
                displayToast(data.error.message, 'liveToast', 'liveToastContent');
            }
        },
        error: function(xhr, status, error) {
            // Afficher un message d'erreur générique en cas d'erreur de requête AJAX
            const toastMessage = 'Une erreur s\'est produite lors de la requête AJAX : ' + error;
            displayToast(toastMessage, 'liveToast', 'liveToastContent');
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
        success: async function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                $('#addUserModal').modal('hide');
                displayToast(data.success, 'liveToast', 'liveToastContent');
                const users = await getAllUsers();
                displayUsersTable(users);
            } else {
                displayToast(data.error.message, 'liveToast', 'liveToastContent');
            }
        },
        error: function(xhr, status, error) {
            // Afficher un message d'erreur générique en cas d'erreur de requête AJAX
            const toastMessage = 'Une erreur s\'est produite lors de la requête AJAX : ' + error;
            displayToast(toastMessage, 'liveToast', 'liveToastContent');
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
        success: async function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                const toastMessage = 'L\'utilisateur a bien été supprimé';
                displayToast(toastMessage, 'liveToast', 'liveToastContent');
                const users = await getAllUsers();
                displayUsersTable(users);
            } else {
                displayToast(data.error.message, 'liveToast', 'liveToastContent');
            }
        },
        error: function(xhr, status, error) {
            // Afficher un message d'erreur générique en cas d'erreur de requête AJAX
            const toastMessage = "Une erreur s'est produite lors de la requête AJAX : " + error;
            displayToast(toastMessage, 'liveToast', 'liveToastContent');
        }
    });
}

/**
 * Récupérer en AJAX la liste des utilisateurs
 *
 * @returns {array} La liste des utilisateurs
 */
async function getAllUsers() {
    try {
        const response = await $.ajax({
            type: 'POST',
            url: 'ajax.php',
            data: {
                ajax: 'getAllUsers'
            }
        });
        const data = JSON.parse(response);
        if (data.success) {
            return data.success;
        } else {
            return { error: data.error };
        }
    } catch (error) {
        const toastMessage = 'Une erreur s\'est produite lors de la requête AJAX : ' + error;
        displayToast(toastMessage, 'liveToast', 'liveToastContent');
    }
}

/**
 * Afficher le tableau des utilisateurs
 *
 * @param {array} users Les utilisateurs 
 */
function displayUsersTable(users) {
    const tbodyUsers = $('#tbodyUsers');
    tbodyUsers.empty();
    users.forEach(
        user => tbodyUsers.append(createUserRow(user))
    );
}

/**
 * Créer une ligne de tableau (<tr>)
 *
 * @param {Object} user - L'objet utilisateur avec les propriétés 'ID' et 'NAME'.
 *
 * @returns {HTMLTableRowElement}
 */
function createUserRow(user) {
    const tr = document.createElement('tr');
    
    const nameTd = createNameTd(user.NAME);
    tr.appendChild(nameTd);

    const actionTd = createActionTd(user.ID, user.NAME);
    tr.appendChild(actionTd);

    return tr;
}

/**
 * Créer le td pour le nom de l'utilisateur
 *
 * @param {string} name Nom de l'utilisateur
 *
 * @returns {HTMLTableCellElement}
 */
function createNameTd(name) {
    const nameTd = document.createElement('td');
    nameTd.textContent = name;
    
    return nameTd
}

/**
 * Créer le td contenant les boutons d'actions
 *
 * @param {int} id id Id de l'utilisateur
 * @param {string} name name Nom de l'utilisateur
 *
 * @returns {HTMLTableCellElement}
 */
function createActionTd(id, name) {
    const actionTd = document.createElement('td');
    actionTd.className = 'text-end';
    
    const editButton = createEditButton(id, name);
    actionTd.appendChild(editButton);
    
    // Ajouter un espace entre les boutons
    const space = document.createTextNode(' '); // Utilise un espace texte
    actionTd.appendChild(space);
    
    const deleteButton = createDeleteButton(id);
    actionTd.appendChild(deleteButton);
    
    return actionTd;
}

/**
 * Créer la cellule pour le bouton "Modifier"
 *
 * @param {int} id Id de l'utilisateur
 * @param {string} name Nom de l'utilisateur
 *
 * @returns {HTMLButtonElement}
 */
function createEditButton(id, name) {
    const editButton = document.createElement('button');
    editButton.className = 'btn btn-warning btn-sm btn-edit';
    // Définir les attributs
    editButton.setAttribute('data-bs-toggle', 'modal');
    editButton.setAttribute('data-bs-target', '#addUserModal');
    editButton.setAttribute('data-user-name', name);
    editButton.setAttribute('data-user-id', id);
    // Ajouter le texte du bouton
    editButton.textContent = 'Modifier';

    return editButton;
}

/**
 * Créer la cellule pour le bouton "Supprimer"
 *
 * @param {int} id Id de l'utilisateur
 *
 * @returns {HTMLButtonElement}
 */
function createDeleteButton(id) {
    const deleteButton = document.createElement('button');
    deleteButton.className = 'btn btn-danger btn-sm';
    deleteButton.textContent = 'Supprimer';
    deleteButton.onclick = function() {
        confirmDelete(id);
    };

    return deleteButton;
}