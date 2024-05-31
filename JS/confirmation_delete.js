$(document).ready(function () {

    const modal = new bootstrap.Modal(document.getElementById('confirmationDelete'));
    const confirmExitButton = $('#confirmExitButton');
    const backButton = $('.back');

    $('.delete').click(function (event) {
        event.preventDefault(); // Empêche le comportement par défaut du formulaire (soumission)
        modal.show();
    });

    confirmExitButton.click(confirmDelete);

    function confirmDelete() {
        // Récupération de l'identifiant de la note depuis une variable PHP
        let requestData = {
            delete: 'delete', // Utilisez .attr('id') pour récupérer l'identifiant du bouton
            note: note
        };
        console.log(requestData);
        $.ajax({
            url: "note/delete_confirmation/" + note, // L'URL où envoyer la requête
            type: "POST", // Méthode de la requête (POST)
            data: requestData, // Les données à envoyer (le titre de la note)
            success: function () { // Fonction exécutée en cas de succès de la requête
                window.location.href = backButton.attr('href');
            },
            error: function (xhr, status, error) { // Fonction exécutée en cas d'erreur de la requête
                console.error("Erreur lors de la supression : ", error); // Affichage de l'erreur dans la console
            }
        });
    }
});