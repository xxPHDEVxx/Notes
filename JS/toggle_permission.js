$(document).ready(function () {
    $('.form_toggle').submit(function (event) {
        event.preventDefault();

        // Identifier l'action soumise
        var action = $(this).find('button[type="submit"]:focus').val();

        // Créer l'objet formData en conséquence
        var formData = {
            note: $(this).find('[name="note"]').val(),
            share: $(this).find('[name="share"]').val(),
            edit: $(this).find('[name="edit"]').val(),
            action: action // Ajouter l'action dans les données à envoyer
        };
        console.log(formData);

        if (action == "toggle") {
            // Envoyer la requête AJAX au serveur
            $.ajax({
                type: 'POST',
                url: 'note/toggle_js',
                data: formData,
                success: function (response) {
                    // Mettre à jour l'affichage selon la réponse du serveur
                    console.log('La requête a été envoyée avec succès.');

                    var role = $(event.target).find('#role').text();
                    console.log(role);
                    if (role == "reader") {
                        $(event.target).find('#role').text("editor");
                    } else {
                        $(event.target).find('#role').text("reader");
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Erreur lors de l\'envoi de la requête : ' + error);
                }
            });
        } else {
            console.log("delete");
        }
    })
})
