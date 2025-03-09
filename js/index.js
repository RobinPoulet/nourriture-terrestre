$(document).ready(function () {
    // Vérifier s'il y a un message stocké dans le sessionStorage
    const toastMessage = sessionStorage.getItem('toastMessage');

    if (toastMessage) {
        // Afficher le toast avec le message stocké
        displayToast(toastMessage, 'liveToast', 'liveToastContent');

        // Supprimer le message du sessionStorage après affichage
        sessionStorage.removeItem('toastMessage');
    }

    const pathname = window.location.pathname;
    const pageName = pathname.split('/').pop();
    if (pageName === 'commande.php') {
        const userSelect = $('#user-select');
        userSelect.select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: userSelect.data('placeholder'),
        });
    }
});

function ajaxSuccess(response, redirectPage) {
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
        window.location.href = baseUrl + '/' + redirectPage;
    } else {
        displayToast(data.error.message, 'liveToast', 'liveToastContent');
    }
}

function ajaxError(error) {
    // Afficher un message d'erreur générique en cas d'erreur de requête AJAX
    const toastMessage = "Une erreur s'est produite lors de la requête AJAX : " + error;
    displayToast(toastMessage, 'liveToast', 'liveToastContent');
}

function displayToast(message, toastId, toastContentId) {
    const toastLive = document.getElementById(toastId);
    const toastContent = document.getElementById(toastContentId);
    toastContent.textContent = message;
    const toast = new bootstrap.Toast(toastLive);
    toast.show();
}

/**
 * AJAX pour valider une commande
 */
function orderValidate() {
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: $("#order-form").serialize(),
        success: function (response) {
            ajaxSuccess(response, 'display-orders.php');
        },
        error: function (xhr, status, error) {
            ajaxError(error);
        }
    });
}

(function () {
    const quantityContainer = document.querySelector(".quantity");
    const minusBtn = quantityContainer.querySelector(".minus");
    const plusBtn = quantityContainer.querySelector(".plus");
    const inputBox = quantityContainer.querySelector(".input-box");

    updateButtonStates();

    quantityContainer.addEventListener("click", handleButtonClick);
    inputBox.addEventListener("input", handleQuantityChange);

    function updateButtonStates() {
        const value = parseInt(inputBox.value);
        minusBtn.disabled = value <= 1;
        plusBtn.disabled = value >= parseInt(inputBox.max);
    }

    function handleButtonClick(event) {
        if (event.target.classList.contains("minus")) {
            decreaseValue();
        } else if (event.target.classList.contains("plus")) {
            increaseValue();
        }
    }

    function decreaseValue() {
        let value = parseInt(inputBox.value);
        value = isNaN(value) ? 1 : Math.max(value - 1, 1);
        inputBox.value = value;
        updateButtonStates();
        handleQuantityChange();
    }

    function increaseValue() {
        let value = parseInt(inputBox.value);
        value = isNaN(value) ? 1 : Math.min(value + 1, parseInt(inputBox.max));
        inputBox.value = value;
        updateButtonStates();
        handleQuantityChange();
    }

    function handleQuantityChange() {
        let value = parseInt(inputBox.value);
        value = isNaN(value) ? 1 : value;

        // Execute your code here based on the updated quantity value
        console.log("Quantity changed:", value);
    }
})();
