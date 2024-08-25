$(document).ready(function () {
    const userSelect = $( '#user-select' );
    userSelect.select2( {
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: userSelect.data( 'placeholder' ),
    });
});

/**
 * AJAX pour valider une commande
 */
function orderValidate() {
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: $("#order-form").serialize(),
        success: function(response) {
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
                displayToast(data.success, 'liveToast', 'liveToastContent')
                document.getElementById('form-card').style.display = 'none';
            }
        },
        error: function(xhr, status, error) {
            // Afficher un message d'erreur générique en cas d'erreur de requête AJAX
            const toastMessage = 'Une erreur s\'est produite lors de la requête AJAX : ' + error;
            displayToast(toastMessage, 'liveToast', 'liveToastContent');
        }
    });
}

function displayToast(message, toastId, toastContentId) {
    const toastLive = document.getElementById(toastId);
    const toastContent = document.getElementById(toastContentId);
    toastContent.textContent = message;
    const toast = new bootstrap.Toast(toastLive);
    toast.show();
}
