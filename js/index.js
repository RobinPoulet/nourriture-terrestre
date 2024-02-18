$(document).ready(function () {
    $("#order-validate").click(function () {
        // Sélectionner tous les éléments dont l'ID commence par 'div-alert'
        const alertes = document.querySelectorAll('[id^="div-alert"]');
        // Parcourir tous les éléments sélectionnés et effacer tous les enfants
        alertes.forEach(element => {
            while (element.firstChild) {
                element.removeChild(element.firstChild);
            }
        });
        // Récupérer les données du formulaire
        const formData = $("#order-form").serialize();
        $.ajax({
            type: "POST",
            url: "order.php",
            data: formData,
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
            }
        });
    });
});
// Fonction pour créer et afficher un toast
function createDivAlert(message, alertId, type) {
    console.log(alertId)
    const root = document.getElementById(alertId);
    const alerte = document.createElement('div');
    const icone = `<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
    </symbol>
    </svg>`;
    alerte.innerHTML = `${type === 'success' ? icone : ''}
        <div class="alert alert-${type} d-flex align-items-center mt-3" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
            <div>
                ${message}
            </div>
        </div>
    `;
    console.log(root, alerte)
    // Ajouter le toast au container
    root.appendChild(alerte);
}