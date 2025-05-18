document.addEventListener("DOMContentLoaded", function () {
    const pathname = window.location.pathname;
    const pageName = pathname.split('/').pop();
    if (pageName === 'display-orders') {
        const completeUrl = document.getElementById('complete-url').value;
        // Sélectionne tous les boutons de suppression
        document.querySelectorAll('.btn-outline-danger').forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.getAttribute('data-order-id');
                if (confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')) {
                    window.location.href = `${completeUrl}/delete-order/${orderId}`;
                }
            })
        })

        const editOrderModal = document.getElementById('editOrderModal');

        // Gestion de l'événement de l'ouverture de la modale
        editOrderModal.addEventListener('show.bs.modal', function (event) {
            // Bouton qui a déclenché la modale
            const button = event.relatedTarget;
            // Récupérer les données du bouton
            const orderId = button.getAttribute('data-order-id');
            const perso = button.getAttribute('data-perso');
            const username = button.getAttribute('data-username');
            const orderDishesData = button.getAttribute('data-order-dishes');
            const inputUserName = document.getElementById('input-user-name');
            inputUserName.value = username;
            const orderDishesObject = JSON.parse(orderDishesData);
            if (
                orderDishesObject
                && username
            ) {
                Object.keys(orderDishesObject).forEach(key => {
                    const element = document.getElementById('dish-' + orderDishesObject[key]["id"]);
                    element.value = orderDishesObject[key]["quantity"];
                })
                const persoInput = document.getElementById('perso');
                const tempElement = document.createElement("div");
                tempElement.innerHTML = perso;
                persoInput.value = tempElement.textContent;
                const editOrderModalLabel = document.getElementById('editOrderModalLabel');
                editOrderModalLabel.textContent = username;
                const editOrderForm = document.getElementById('edit-order-form');
                editOrderForm.action = `${completeUrl}/edit-order/${orderId}`;
                editOrderForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    if (confirm('Êtes-vous sûr de vouloir modifier cette commande ?')) {
                        editOrderForm.submit();
                    }
                })
            }
        });

        editOrderModal.addEventListener('hidden.bs.modal', function () {
            const persoInput = document.getElementById('perso');
            persoInput.value = "";
            const editOrderModalLabel = document.getElementById('editOrderModalLabel');
            editOrderModalLabel.textContent = "";
            // Sélectionner tous les inputs qui ont "dish" dans leur attribut name
            const dishInputs = document.querySelectorAll('input[name*="dish"]');
            // Remettre leur value à 0
            dishInputs.forEach(input => {
                input.value = 0;
            });
            const editOrderForm = document.getElementById('edit-order-form');
            editOrderForm.action = '';
        });
    }
});