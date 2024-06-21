$(document).ready(function () {
    var boxLabels = $('.box-label:visible');
    
    if(boxLabels.length == 0) {
        $('#label-empty').show();
    } else {
        $('#label-empty').hide();
    }
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
                    titleErrorDiv.show();
                    titleErrorDiv.text(data);
                    
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                    titleErrorDiv.hide();
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
                    form.appendTo('.box-labels');
    
                    //on remet l'input pour le nv label vide
                    $(document).find('[name="new_label"]').val("");

                    boxLabels = $('.box-label:visible');
                    console.log(boxLabels.length);
                    if(boxLabels.length == 0) {
                        $('#label-empty').show();
                    } else {
                        $('#label-empty').hide();
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('Erreur lors de l\'envoi de la requête : ' + error);
            }
        });


    })

    $(".form_delete").submit(function (event) {
        event.preventDefault();
    

        //récuperer les données du form
        // Identifier l'action soumise
        var note = $(this).find('[name="note_id"]').val()
        console.log( $(this).find('[name="label"]').val());
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
                $(event.target).find('.box-label').hide();


            },
            error: function (xhr, status, error) {
                console.error('Erreur lors de l\'envoi de la requête : ' + error);
            }
        });

        var box = $('.box-label:visible');
        if(box.length-1 == 0) {
            $('#label-empty').show();
        } else {
            $('#label-empty').hide();
        }


    })

})