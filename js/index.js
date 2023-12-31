$(document).ready(function () {
    $("#order-validate").click(function () {
        // Récupérer les données du formulaire
        const formData = $("#order-form").serialize();
        $.ajax({
            type: "POST",
            url: "order.php",
            data: formData,
            success: function(response) {
                const data = JSON.parse(response);
                console.log(data, JSON.parse(data[1]))
                createDivAlert(data)
                document.getElementById('form-card').style.display = 'none';
            }
        });
    });
});
// Fonction pour créer et afficher un toast
function createDivAlert(data) {
    [user, order] = data
    const root = document.getElementById('div-alert')
    const alerte = document.createElement('div');
    alerte.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </symbol>
        </svg>
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
            <div>
                Ta comande est bien enregirstrée ${user}
            </div>
        </div>
    `;

    // Ajouter le toast au container
    root.appendChild(alerte);
}

function deleteOrder(orderId) {
    $.ajax({
        type: "POST",
        url: "display-orders.php",
        data: {
            ajax: "deleteOrder",
            deleteId: orderId
        },
        success: function (response) {
            const data = JSON.parse(response)
            console.log(data);
            const id = "trid" + data.deleted;
            const tr = document.getElementById(id);
            tr.remove();
        }
    });
 }
