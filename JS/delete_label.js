$(document).ready(function () {
    $("#form_delete").submit(function (event) {
        event.preventDefault();

        //récuperer les données du form
        // Identifier l'action soumise
        var note = $(this).find('[name="note_id"]').val()
        // Créer l'objet formData en conséquence
        var formData = {
            note: note,
            label: $(this).find('[name="label"]').val(),
        };
        // // Envoyer la requête AJAX au serveur
        $.ajax({
            type: 'POST',
            url: "note/delete_label_service/"+ note,
            data: formData,
            success: function (response) {
                // Mettre à jour l'affichage selon la réponse du serveur
                console.log('La requête a été envoyée avec succès.');
                console.log(response);
                $('.box-label').hide();

            },
            error: function (xhr, status, error) {
                console.error('Erreur lors de l\'envoi de la requête : ' + error);
            }
        });


    })
})