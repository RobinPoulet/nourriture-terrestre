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
            const orderObject = JSON.parse(orderData);
            if (orderObject && username) {
                const orderIdInput = document.getElementById('order-id');
                orderIdInput.value = orderObject.ID;
                const persoInput = document.getElementById('perso');
                persoInput.value = orderObject.PERSO;
                const editOrderModalLabel = document.getElementById('editOrderModalLabel');
                editOrderModalLabel.textContent = username;
                const orderContent = JSON.parse(orderObject.CONTENT);
                console.log(orderContent);
                Object.keys(orderContent).forEach(key => {
                    document.getElementById(key).checked = true;
                });
                const orderEditValidate = document.getElementById('order-edit-validate');
                orderEditValidate.onclick = function () {
                    confirmOrderEdit(username);
                }
            }
        });

        editOrderModal.addEventListener('hidden.bs.modal', function () {
            const orderIdInput = document.getElementById('order-id');
            orderIdInput.value = null;
            const persoInput = document.getElementById('perso');
            persoInput.value = "";
            const editOrderModalLabel = document.getElementById('editOrderModalLabel');
            editOrderModalLabel.textContent = "";
            ["entree", "plat-1", "plat-2", "dessert-1", "dessert-2"].forEach(key => document.getElementById(key).checked = false);
            const orderEditValidate = document.getElementById('order-edit-validate');
            orderEditValidate.onclick = null;
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
            ajaxSuccess(response, 'display-orders.php');
        },
        error: function (xhr, status, error) {
            ajaxError(error);
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
            ajaxSuccess(response, 'display-orders.php');
        },
        error: function (xhr, status, error) {
            ajaxError(error);
        }
    });
}