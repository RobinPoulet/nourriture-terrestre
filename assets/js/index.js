document.addEventListener("DOMContentLoaded", function () {
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