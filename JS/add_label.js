$(document).ready(function () {
    $('#add_label').submit(function (event) {
        event.preventDefault();

        //récuperer les données du form
        var formData = $(this).serialize();
        var note = $('#note_id').val();
        var label = $(this).find('[name="new_label"]').val();
        var titleErrorDiv = $("#newContentError");

        console.log(note);
        // // Envoyer la requête AJAX au serveur
        $.ajax({
            type: 'POST',
            url: "note/add_label_service/"+ note,
            data: formData,
            success: function (data) {
                // Mettre à jour l'affichage selon la réponse du serveur
                console.log('La requête a été envoyée avec succès.');
                if(data != "") {
                    console.log(data);
                    titleErrorDiv.text(data);
                    console.log(titleErrorDiv);
                    
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);

                    // Créer le formulaire pour le delete
                    var form = $('<form>').addClass('form_toggle').attr('action', 'note/delete_label/' + note).attr('method', 'post');
                    //on créé la box label
                    var boxLabel = $('<div>').addClass('box-label');
    
                    //on crée les input
                    var inputNote = $('<input>').attr('type', 'text').attr('id', 'note_id').attr('name', 'note_id').val(note).hide();
                    var inputLabel = $('<input>').attr('type', 'text').attr('id', 'label').attr('name', 'label').val(label);
                    inputLabel.addClass('form-control-label my-1');
    
                    //bouton delte 
                    var buttonDelete = $('<button>').addClass('btn btn-danger btn-share btn-del').attr('type', 'submit').attr('name', 'label').val(label);
                    var deleteIcon = $('<span>').addClass('material-symbols-outlined').text('minimize');
                    buttonDelete.append(deleteIcon);
    
                    // Assembler les éléments
                    boxLabel.append(inputNote).append(inputLabel).append(buttonDelete);
                    form.append(boxLabel);
    
                    // Ajouter le formulaire à l'endroit désiré dans le DOM
                    form.appendTo('.box-all');
    
                    //on remet l'input pour le nv label vide
                    $(document).find('[name="new_label"]').val("");

                }
            },
            error: function (xhr, status, error) {
                console.error('Erreur lors de l\'envoi de la requête : ' + error);
            }
        });


    })


})