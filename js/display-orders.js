$(document).ready(function () {
    const pathname = window.location.pathname;
    const pageName = pathname.split('/').pop();
    if (pageName === 'display-orders.php') {
        const editOrderModal = document.getElementById('editOrderModal');
        // Gestion de l'événement de l'ouverture de la modale
        editOrderModal.addEventListener('show.bs.modal', function (event) {
            // Bouton qui a déclenché la modale
            const button = event.relatedTarget;

            // Récupérer les données du bouton
            const orderData = button.dataset.order;
            const username = button.dataset.username;
            const orderObject = JSON.parse(orderData)[0];
            if (orderObject && username) {
                const orderIdInput = document.getElementById('order-id');
                orderIdInput.value = orderObject.ID;
                const persoInput = document.getElementById('perso');
                persoInput.value = orderObject.PERSO;
                const editOrderModalLabel = document.getElementById('editOrderModalLabel');
                editOrderModalLabel.textContent = username;
                const orderContent = JSON.parse(orderObject.CONTENT);
                Object.keys(orderContent).forEach(key => {
                    document.getElementById(key).checked = true;
                });
                const orderEditValidate = document.getElementById('order-edit-validate');
                orderEditValidate.onclick = function () {
                    confirmOrderEdit(username);
                }
            }
        });
    }
});

/**
 * Confirmer l'édition d'une commande
 *
 */
function confirmOrderEdit() {
    if (confirm('Êtes-vous sûr de vouloir modifier cette commande ?')) {
        editOrder();
    }
}

/**
 * AJAX pour éditer une commande
 */
function editOrder() {
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: $("#edit-order-form").serialize(),
        success: function (response) {
            const data = JSON.parse(response);
            if (data.error) {
                displayToast(data.error.message, 'liveToast', 'liveToastContent');
            }
            if (data.errors) {
                data.errors.forEach(error => {
                    displayToast(error.message, 'liveToast', 'liveToastContent');
                });
            }
            if (data.success) {
                // Déterminer l'URL de redirection en fonction de l'environnement
                const baseUrl = window.location.origin;
                // Rediriger vers l'URL calculée
                window.location.href = baseUrl + '/display-orders.php';
                displayToast(data.success.message, 'liveToast', 'liveToastContent')
            }
        },
        error: function (xhr, status, error) {
            // Afficher un message d'erreur générique en cas d'erreur de requête AJAX
            const toastMessage = 'Une erreur s\'est produite lors de la requête AJAX : ' + error;
            displayToast(toastMessage, 'liveToast', 'liveToastContent');
        }
    });
}


/**
 * Confirmer la suppression d'une commande
 *
 */
function confirmOrderDelete(orderId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')) {
        deleteOrder(orderId);
    }
}


/**
 * AJAX pour supprimer une commande
 */
function deleteOrder(orderId) {
    const data = {
        ajax: 'delete-order',
        id: orderId
    }
    $.ajax({
        type: 'POST',
        url: 'ajax.php',
        data: data,
        success: function (response) {
            const data = JSON.parse(response);
            if (data.success) {
                // Déterminer l'URL de redirection en fonction de l'environnement
                const baseUrl = window.location.origin;
                // Rediriger vers l'URL calculée
                window.location.href = baseUrl + '/display-orders.php';
                displayToast(data.success, 'liveToast', 'liveToastContent');
            } else {
                displayToast(data.error.message, 'liveToast', 'liveToastContent');
            }
        },
        error: function (xhr, status, error) {
            // Afficher un message d'erreur générique en cas d'erreur de requête AJAX
            const toastMessage = "Une erreur s'est produite lors de la requête AJAX : " + error;
            displayToast(toastMessage, 'liveToast', 'liveToastContent');
        }
    });
}