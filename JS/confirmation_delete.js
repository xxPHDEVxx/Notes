$(document).ready(function () {

    const modal = new bootstrap.Modal(document.getElementById('confirmationDelete'));
    const confirmExitButton = $('#confirmExitButton');
    const backButton = $('.back'); 

    $('.delete').click(function (event) {
        event.preventDefault(); // Empêche le comportement par défaut du formulaire (soumission)
        modal.show();
    });

    confirmExitButton.click(function () {
        // L'utilisateur confirme vouloir quitter, continuez la navigation
        $.ajax({
            url: $('#deleteForm').attr('action'), 
            type: 'POST', 
            success: function (response) {
                window.location.href = backButton.attr('href'); // Assurez-vous que l'attribut href de backButton est correctement défini
            },
            error: function (xhr, status, error) {
                // En cas d'erreur lors de la suppression de la note
                console.error(error);
                // Gérer l'erreur en conséquence
            }
        });
    });
});