$(document).ready(function () {    
    $( '#user-select' ).select2( {
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $( '#user-select' ).data( 'placeholder' ),
    });
});

/**
 * AJAX pour valider une commande
 */
function orderValidate() {
    $.ajax({
        type: "POST",
        url: "order.php",
        data: $("#order-form").serialize(),
        success: function(response) {
            const data = JSON.parse(response);
            if (data.error) {
                createDivAlert(data.error.message, 'div-alert-' + data.error.type, 'danger');
            }
            if (data.errors) {
                data.errors.forEach(error => {
                    createDivAlert(error.message, 'div-alert-' + error.type, 'danger');
                });
            }
            if (data.success) {
                createDivAlert(data.success, 'div-alert', 'success')
                document.getElementById('form-card').style.display = 'none';
            }
        },
        error: function(xhr, status, error) {
            // Afficher un message d'erreur générique en cas d'erreur de requête AJAX
            createDivAlert("Une erreur s'est produite lors de la requête AJAX : " + error, 'div-alert-request', 'danger');
        }
    });
}

/**
 * Créer et afficher un toast 
 *
 * @param {string} message Contenu du message
 * @param {int} alertId Id de la div d'alerte pour affichage du message
 * @param {string} type Type d'alerte
 */
function createDivAlert(message, alertId, type) {
    const displayTime = (type === 'danger' ? 8000 : 4000);
    // Vider le conteneur
    $('#' + alertId).empty();
    const root = document.getElementById(alertId);

    // Créer l'élément alerte
    const alerte = document.createElement('div');

    // SVG pour les icônes (d'autres icônes peuvent être ajoutées ici si nécessaire)
    const icons = `
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </symbol>
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 0c4.418 0 8 3.582 8 8s-3.582 8-8 8-8-3.582-8-8 3.582-8 8-8zm.93 4.418a.455.455 0 0 0-.857 0l-.92 3.684c-.065.259.128.506.399.506h2.458c.271 0 .464-.247.399-.506L8.93 4.418zm.587 7.482a.655.655 0 1 1-1.31 0 .655.655 0 0 1 1.31 0z"/>
        </symbol>
    </svg>`;

    // Définir le contenu de l'alerte
    alerte.innerHTML = `
        ${icons}
        <div class="d-flex justify-content-center text-center">
            <div class="alert alert-${type} alert-dismissible d-flex align-items-center mt-3 w-50" role="alert">
                ${type === 'success' ? '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>' : ''}
                ${type === 'danger' ? '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>' : ''}
                <div>
                    ${message}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    `;

    // Ajouter l'alerte au conteneur
    root.appendChild(alerte);

    // Configuration pour fermer automatiquement l'alerte après 2 secondes
    setTimeout(() => {
        if (alerte && alerte.parentElement) {
            alerte.parentElement.removeChild(alerte);
        }
    }, displayTime);

    // Fermer l'alerte immédiatement lorsqu'on clique sur le bouton de fermeture
    alerte.querySelector('.btn-close').addEventListener('click', () => {
        if (alerte && alerte.parentElement) {
            alerte.parentElement.removeChild(alerte);
        }
    });
}