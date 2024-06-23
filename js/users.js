$(document).ready(function () { 
    // Sélectionner les éléments nécessaires
    const addUserModal = document.getElementById('addUserModal');
    const userNameInput = document.getElementById('userName');
    const userValidateButton = document.getElementById('user-validate');

    // Fonction pour réinitialiser la modale au mode ajout
    function resetModalToAddMode() {
        document.getElementById('addUserModalLabel').textContent = 'Ajouter un Utilisateur';
        userNameInput.value = '';
        userValidateButton.textContent = 'Ajouter';
        userValidateButton.onclick = function() {
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

        if (userId && userName) {
            // Si l'édition est en cours
            document.getElementById('addUserModalLabel').textContent = 'Éditer un Utilisateur';
            userNameInput.value = userName;
            userValidateButton.textContent = 'Modifier';
            userValidateButton.onclick = function() {
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
        url: 'ajax-user.php',
        data: data,
        success: async function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                $('#addUserModal').modal('hide');
                createDivAlert(data.success, 'div-alert-request', 'success');
                const users = await getAllUsers();
                displayUsersTable(users);
            } else {
                createDivAlert(data.error.message, 'div-alert' + data.error.type, 'danger')
            }
        },
        error: function(xhr, status, error) {
            // Afficher un message d'erreur générique en cas d'erreur de requête AJAX
            createDivAlert("Une erreur s'est produite lors de la requête AJAX : " + error, 'div-alert-request', 'danger');
        }
    });
}

function confirmEdit(userId) {
    if (confirm('Êtes-vous sûr de vouloir modifier cet utilisateur ?')) {
        editUser(userId);
    }
}

/**
 * AJAX pour l'ajout d'utilisateur
 */
function editUser(userId) {
    const data = {
        name : document.getElementById('userName').value,
        id: userId,
        ajax: 'edit'
    };
    $.ajax({
        type: 'POST',
        url: 'ajax-user.php',
        data: data,
        success: async function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                $('#addUserModal').modal('hide');
                createDivAlert(data.success, 'div-alert-request', 'success');
                const users = await getAllUsers();
                displayUsersTable(users);
            } else {
                createDivAlert(data.error.message, 'div-alert' + data.error.type, 'danger')
            }
        },
        error: function(xhr, status, error) {
            // Afficher un message d'erreur générique en cas d'erreur de requête AJAX
            createDivAlert("Une erreur s'est produite lors de la requête AJAX : " + error, 'div-alert-request', 'danger');
        }
    });
}

function confirmDelete(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        deleteUser(userId);
    }
}

function deleteUser(userId) {
    const data = {
        ajax : 'delete',
        id : userId
    }
    $.ajax({
        type: 'POST',
        url: 'ajax-user.php',
        data: data,
        success: async function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                createDivAlert(data.success, 'div-alert-request', 'success');
                const users = await getAllUsers();
                displayUsersTable(users);
            } else {
                createDivAlert(data.error.message, 'div-alert' + data.error.type, 'danger');
            }
        },
        error: function(xhr, status, error) {
            // Afficher un message d'erreur générique en cas d'erreur de requête AJAX
            createDivAlert("Une erreur s'est produite lors de la requête AJAX : " + error, 'div-alert-request', 'danger');
        }
    });
}

async function getAllUsers() {
    try {
        const response = await $.ajax({
            type: 'POST',
            url: 'ajax-user.php',
            data: {
                ajax: 'getAllUsers'
            }
        });
        const data = JSON.parse(response);
        if (data.success) {
            return data.success;
        } else {
            throw new Error(data.error); // Lancer une erreur en cas de réponse avec erreur
        }
    } catch (error) {
        createDivAlert("Une erreur s'est produite lors de la requête AJAX : " + error, 'div-alert-request', 'danger');
    }
}

function displayUsersTable(users) {
    $('#tbodyUsers').empty();
    users.forEach(user => {
        const tr = createUserRow(user);
        $('#tbodyUsers').append(tr);
    })
}

/**
 * Fonction pour créer une ligne de tableau (<tr>)
 *
 * @param {Object} user - L'objet utilisateur avec les propriétés 'ID' et 'NAME'.
 *
 * @returns {HTMLTableRowElement}
 */
function createUserRow(user) {
    const tr = document.createElement('tr');

    const nameTd = createUserNameCell(user.NAME);
    tr.appendChild(nameTd);

    const editTd = createUserEditButton(user.ID, user.NAME);
    tr.appendChild(editTd);

    const deleteTd = createDeleteUserButton(user.ID);
    tr.appendChild(deleteTd);

    return tr;
}

function createUserNameCell(name) {
    // Créer la cellule pour le nom de l'utilisateur
    const nameTd = document.createElement('td');
    nameTd.textContent = name;
    
    return nameTd
}

function createUserEditButton(id, name) {
    // Créer la cellule pour le bouton "Modifier"
    const editTd = document.createElement('td');
    editTd.className = 'text-center';
    const editButton = document.createElement('button');
    editButton.className = 'btn btn-warning btn-sm btn-edit';
    // Définir les attributs
    editButton.setAttribute('data-bs-toggle', 'modal');
    editButton.setAttribute('data-bs-target', '#addUserModal');

    editButton.setAttribute('data-user-name', name);
    editButton.setAttribute('data-user-id', id);

    // Ajouter le texte du bouton
    editButton.textContent = 'Modifier';
    editTd.appendChild(editButton);
    
    return editTd;
}

function createDeleteUserButton(id) {
    // Créer la cellule pour le bouton "Supprimer"
    const deleteTd = document.createElement('td');
    deleteTd.className = 'text-center';
    const deleteButton = document.createElement('button');
    deleteButton.className = 'btn btn-danger btn-sm';
    deleteButton.textContent = 'Supprimer';
    deleteButton.onclick = function() {
        confirmDelete(id);
    };
    deleteTd.appendChild(deleteButton);
    
    return deleteTd;
}