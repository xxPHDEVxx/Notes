$(document).ready(function () {
    var boxLabels = $('.box-label:visible');
    
    if(boxLabels.length == 0) {
        $('#label-empty').show();
    } else {
        $('#label-empty').hide();
    }
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
                $('.box-label').hide();

                boxLabels = $('.box-label:visible')
                if(boxLabels.length == 0) {
                    $('#label-empty').show();
                } else {
                    $('#label-empty').hide();
                }
            },
            error: function (xhr, status, error) {
                console.error('Erreur lors de l\'envoi de la requête : ' + error);
            }
        });


    })
})